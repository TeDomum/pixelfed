<?php

namespace App\Jobs\StatusPipeline;

use App\Hashtag;
use App\Jobs\MentionPipeline\MentionPipeline;
use App\Mention;
use App\Profile;
use App\Status;
use App\StatusHashtag;
use App\Util\Lexer\Autolink;
use App\Util\Lexer\Extractor;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StatusEntityLexer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;
    protected $entities;
    protected $autolink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->status;
        $this->parseEntities();
    }

    public function parseEntities()
    {
        $this->extractEntities();
    }

    public function extractEntities()
    {
        $this->entities = Extractor::create()->extract($this->status->caption);
        $this->autolinkStatus();
    }

    public function autolinkStatus()
    {
        $this->autolink = Autolink::create()->autolink($this->status->caption);
        $this->storeEntities();
    }

    public function storeEntities()
    {
        $this->storeHashtags();
        $this->storeMentions();
        DB::transaction(function () {
            $status = $this->status;
            $status->rendered = nl2br($this->autolink);
            $status->entities = json_encode($this->entities);
            $status->save();
        });
    }

    public function storeHashtags()
    {
        $tags = array_unique($this->entities['hashtags']);
        $status = $this->status;

        foreach ($tags as $tag) {
            DB::transaction(function () use ($status, $tag) {
                $slug = str_slug($tag, '-', false);
                $hashtag = Hashtag::firstOrCreate(
                    ['name' => $tag, 'slug' => $slug]
                );
                StatusHashtag::firstOrCreate(
                    ['status_id' => $status->id, 'hashtag_id' => $hashtag->id]
                );
            });
        }
    }

    public function storeMentions()
    {
        $mentions = array_unique($this->entities['mentions']);
        $status = $this->status;

        foreach ($mentions as $mention) {
            $mentioned = Profile::whereUsername($mention)->firstOrFail();

            if (empty($mentioned) || !isset($mentioned->id)) {
                continue;
            }

            DB::transaction(function () use ($status, $mentioned) {
                $m = new Mention();
                $m->status_id = $status->id;
                $m->profile_id = $mentioned->id;
                $m->save();

                MentionPipeline::dispatch($status, $m);
            });
        }
    }
}
