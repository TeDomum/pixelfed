<?php

namespace App\Util\ActivityPub;

use App\Jobs\AvatarPipeline\CreateAvatar;
use App\{Follower, Like, Notification, Profile, Like, Status, User};

class Inbox {

    protected $request;
    protected $profile;
    protected $payload;

    public function __construct($request, Profile $profile, $payload)
    {
        $this->request = $request;
        $this->profile = $profile;
        $this->payload = $payload;
    }

    public function handle()
    {
        $this->authenticatePayload();
    }

    public function authenticatePayload()
    {
        // todo

        $this->handleVerb();
    }

    public function handleVerb()
    {
        $verb = $this->payload['type'];

        switch ($verb) {
            case 'Create':
                $this->handleCreateActivity();
                break;

            case 'Follow':
                $this->handleFollowActivity();
                break;

            default:
                // TODO: decide how to handle invalid verbs.
                break;
        }
    }

    public function handleCreateActivity()
    {
        // todo
    }

    public function handleFollowActivity()
    {
        $actor = $this->payload['object'];
        $target = $this->profile;

        $actorProfile = $this->actorFirstOrCreate($actor);

        // TODO: Send notification to local user about Follow to Accept/Reject
        $this->followActivityNotification($actorProfile);
    }

    public function actorFirstOrCreate($actorUrl)
    {
        if(Profile::whereRemoteUrl($actorUrl)->count() !== 0) {
            return Profile::whereRemoteUrl($actorUrl)->firstOrFail();
        }

        $res = (new DiscoverActor($url))->discover();

        $domain = parse_url($res['url'], PHP_URL_HOST);
        $username = $res['preferredUsername'];
        $remoteUsername = "@{$username}@{$domain}";

        $profile = new Profile;
        $profile->user_id = null;
        $profile->domain = $domain;
        $profile->username = $remoteUsername;
        $profile->name = $res['name'];
        $profile->bio = str_limit($res['summary'], 125);
        $profile->sharedInbox = $res['endpoints']['sharedInbox'];
        $profile->remote_url = $res['url'];
        $profile->save();

        CreateAvatar::dispatch($profile);

        return $profile;
    }

    public function followActivityNotification(Profile $actor)
    {
        $this->followActivityAccept($actor);
    }

    public function followActivityAccept(Profile $actor)
    {
        $follower = new Follower;
        $follower->profile_id = $actor->id;
        $follower->following_id = $this->profile->id;
        $follower->save();

        $notification = new Notification;
        $notification->profile_id = $target->id;
        $notification->actor_id = $actor->id;
        $notification->action = 'follow';
        $notification->message = $follower->toText();
        $notification->rendered = $follower->toHtml();
        $notification->save();
    }

}