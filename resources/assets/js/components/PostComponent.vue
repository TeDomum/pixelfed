<style>

</style>
<template>
<div class="postComponent">
  <div class="container px-0 mt-md-4">
    <div class="card card-md-rounded-0 status-container orientation-unknown">
      <div class="row mx-0">
      <div class="d-flex d-md-none align-items-center justify-content-between card-header bg-white w-100">
        <a :href="statusProfileUrl" class="d-flex align-items-center status-username text-truncate" data-toggle="tooltip" data-placement="bottom" :title="statusUsername">
          <div class="status-avatar mr-2">
            <img :src="statusAvatar" width="24px" height="24px" style="border-radius:12px;">
          </div>
          <div class="username">
            <span class="username-link font-weight-bold text-dark">{{ statusUsername }}</span>
          </div>
        </a>
        <div class="float-right">
          <div class="post-actions d-none">
          <div class="dropdown">
            <button class="btn btn-link text-dark no-caret dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Post options">
            <span class="fas fa-ellipsis-v text-muted"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item font-weight-bold" :href="reportUrl()">Report</a>
                <div class="dropdown-divider"></div>
                <form method="post" action="/i/mute">
                  <input type="hidden" name="_token" value="">            
                  <input type="hidden" name="type" value="user">
                  <input type="hidden" name="item" value="">
                  <button type="submit" class="dropdown-item btn btn-link font-weight-bold">Mute this user</button>
                </form>
                <form method="post" action="/i/block">
                  <input type="hidden" name="_token" value="">            
                  <input type="hidden" name="type" value="user">
                  <input type="hidden" name="item" value="">
                  <button type="submit" class="dropdown-item btn btn-link font-weight-bold">Block this user</button>
                </form>
              </div>
            </div>
          </div>
        </div>
       </div>
        <div class="col-12 col-md-8 status-photo px-0">
            <div class="postPresenterLoader text-center">
              <div class="lds-ring"><div></div><div></div><div></div><div></div></div> 
            </div>
            <div class="postPresenterContainer d-none">

            </div>
        </div>

        <div class="col-12 col-md-4 px-0 d-flex flex-column border-left border-md-left-0">
          <div class="d-md-flex d-none align-items-center justify-content-between card-header py-3 bg-white">
            <a :href="statusProfileUrl" class="d-flex align-items-center status-username text-truncate" data-toggle="tooltip" data-placement="bottom" :title="statusUsername">
              <div class="status-avatar mr-2">
                <img :src="statusAvatar" width="24px" height="24px" style="border-radius:12px;">
              </div>
              <div class="username">
                <span class="username-link font-weight-bold text-dark">{{ statusUsername }}</span>
              </div>
            </a>
              <div class="float-right">
                <div class="post-actions d-none">
                <div class="dropdown">
                  <button class="btn btn-link text-dark no-caret dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Post options">
                  <span class="fas fa-ellipsis-v text-muted"></span>
                  </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item font-weight-bold" :href="reportUrl()">Report</a>
                        <div class="dropdown-divider"></div>
                        <form method="post" action="/i/mute">
                          <input type="hidden" name="_token" value="">            
                          <input type="hidden" name="type" value="user">
                          <input type="hidden" name="item" value="">
                          <button type="submit" class="dropdown-item btn btn-link font-weight-bold">Mute this user</button>
                        </form>
                        <form method="post" action="/i/block">
                          <input type="hidden" name="_token" value="">            
                          <input type="hidden" name="type" value="user">
                          <input type="hidden" name="item" value="">
                          <button type="submit" class="dropdown-item btn btn-link font-weight-bold">Block this user</button>
                        </form>
                      </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="d-flex flex-md-column flex-column-reverse h-100">
            <div class="card-body status-comments">
              <div class="status-comment">
                <p class="mb-1 read-more" style="overflow: hidden;">
                  <span class="font-weight-bold pr-1">{{statusUsername}}</span>
                  <span class="comment-text" :id="status.id + '-status-readmore'"></span>
                </p>
                <post-comments :user="this.user" :post-id="statusId" :post-username="statusUsername"></post-comments>
              </div>
            </div>
            <div class="card-body flex-grow-0 py-1">
              <div class="reactions my-1">
                <form class="d-inline-flex pr-3 like-form" method="post" action="/i/like" style="display: inline;" :data-id="statusId" data-action="like">
                  <input type="hidden" name="_token" value="">
                  <input type="hidden" name="item" :value="statusId">
                  <button class="btn btn-link text-dark p-0 border-0" type="submit" title="Like!">
                    <h3 class="status-heart m-0 far fa-heart text-dark"></h3>
                  </button>
                </form>
                <h3 class="far fa-comment pr-3 m-0" title="Comment" v-on:click="commentFocus"></h3>
                <form class="d-inline-flex share-form pr-3" method="post" action="/i/share" style="display: inline;" data-id="11todo" data-action="share" data-count="status.favourite_count">
                  <input type="hidden" name="_token" value="">
                  <input type="hidden" name="item" :value="statusId">
                  <button class="btn btn-link text-dark p-0" type="submit" title="Share">
                    <h3 class="m-0 far fa-share-square"></h3>
                  </button>
                </form>

                <span class="float-right">
                  <form class="d-inline-flex" method="post" action="/i/bookmark" style="display: inline;" data-id="#" data-action="bookmark" onclick="this.preventDefault()">
                    <input type="hidden" name="_token" value="">
                    <input type="hidden" name="item" :value="statusId">
                    <button class="btn btn-link text-dark p-0 border-0" type="submit" title="Save">
                      <h3 class="m-0 far fa-bookmark"></h3>
                    </button>
                  </form>
                </span>
              </div>
              <div class="likes font-weight-bold mb-0">
                <span class="like-count">{{status.favourites_count || 0}}</span> likes
              </div>
              <div class="timestamp">
                <a v-bind:href="statusUrl" class="small text-muted">
                  {{timestampFormat()}}
                </a>
              </div>
            </div>
          </div>
          <div class="card-footer bg-white sticky-md-bottom">
            <div class="comment-form-guest">
              <a href="/login">Login</a> to like or comment.
            </div>
            <form class="comment-form d-none" method="post" action="/i/comment" :data-id="statusId" data-truncate="false">
              <input type="hidden" name="_token" value="">
              <input type="hidden" name="item" :value="statusId">

              <input class="form-control" name="comment" placeholder="Add a comment..." autocomplete="off">
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
</template>

<script>

pixelfed.postComponent = {};
pixelfed.presenter = {
  show: {
    image: function(container, media) {
      $('.status-container')
        .removeClass('orientation-unknown')
        .addClass('orientation-' + media[0]['orientation']);
      let wrapper = $('<div>');
      wrapper.addClass(media[0]['filter_class']);
      let el = $('<img>');
      el.attr('src', media[0]['url']);
      el.attr('title', media[0]['description']);
      wrapper.append(el);
      container.append(wrapper);
    },

    video: function(container, media) {
      let wrapper = $('<div>');
      wrapper.addClass('embed-responsive embed-responsive-4by3');
      let el = $('<video>');
      el.addClass('embed-responsive-item');
      el.attr('controls', '');
      el.attr('src', media[0]['url']);
      el.attr('title', media[0]['description']);
      wrapper.append(el);
      container.append(wrapper);
    },

    imageAlbum: function(container, media, status) {
      $('.status-container')
        .removeClass('orientation-unknown')
        .addClass('orientation-' + media[0]['orientation']);
      let id = 'photo-carousel-wrapper-' + status.id;
      let wrapper = $('<div>');
      wrapper.addClass('carousel slide carousel-fade');
      wrapper.attr('data-ride', 'carousel');
      wrapper.attr('id', id);
      let indicators = $('<ol>');
      indicators.addClass('carousel-indicators');
      let prev = $('<a>');
      prev.addClass('carousel-control-prev');
      prev.attr('href', '#' + id);
      prev.attr('role', 'button');
      prev.attr('data-slide', 'prev');
      let prevIcon = $('<span>').addClass('carousel-control-prev-icon').attr('aria-hidden', 'true');
      let prevSr = $('<span>').addClass('sr-only');
      prev.append(prevIcon, prevSr);
      let next = $('<a>');
      next.addClass('carousel-control-next');
      next.attr('href', '#' + id);
      next.attr('role', 'button');
      next.attr('data-slide', 'next');
      let nextIcon = $('<span>').addClass('carousel-control-next-icon').attr('aria-hidden', 'true');
      let nextSr = $('<span>').addClass('sr-only');
      let inner = $('<div>').addClass('carousel-inner');
      next.append(nextIcon, nextSr);
      for(let i = 0; i < media.length; i++) {
        let li = $('<li>');
        li.attr('data-target', '#' + id);
        li.attr('data-slide-to', i);
        if(i == 0) {
          li.addClass('active');
        }
        indicators.append(li);
        let item = media[i];
        let carouselItem = $('<div>').addClass('carousel-item');
        if(i == 0) {
          carouselItem.addClass('active');
        }
        let figure = $('<figure>');
        if(item['filter_class']) {
          figure.addClass(item['filter_class']);
        }

        let badge = $('<span>');
        badge.addClass('float-right mr-3 badge badge-dark');
        badge.style = 'position:fixed;top:8px;right:0;margin-bottom:-20px;';
        badge.text(i+1 + '/' + media.length);

        let img = $('<img>');
        img.addClass('d-block w-100');
        img.attr('src', item['url']);

        figure.append(badge, img);
        carouselItem.append(figure);

        inner.append(carouselItem);
      }
      wrapper.append(indicators, inner, prev, next);
      container.append(wrapper);
    }
  }
};

export default {
    props: ['status-id', 'status-username', 'status-template', 'status-url', 'status-profile-url', 'status-avatar'],
    data() {
        return {
            status: {},
            media: {},
            user: {},
            reactions: {}
          }
    },
    mounted() {
      let token = $('meta[name="csrf-token"]').attr('content');
      $('input[name="_token"]').each(function(k, v) {
          let el = $(v);
          el.val(token);
      });
      this.fetchData();
      //pixelfed.hydrateLikes();
      this.authCheck();
    },
    updated() {
      $('.carousel').carousel();
      if(this.reactions) {
        if(this.reactions.bookmarked == true) {
          $('.far.fa-bookmark').removeClass('far').addClass('fas text-warning');
        }
        if(this.reactions.shared == true) {
          $('.far.fa-share-square').addClass('text-primary');
        }
        if(this.reactions.liked == true) {
          $('.far.fa-heart ').removeClass('far text-dark').addClass('fas text-danger');
        }
      }
    },
    methods: {
      authCheck() {
        let authed = $('body').hasClass('loggedIn');
        if(authed == true) {
          $('.comment-form-guest').addClass('d-none');
          $('.comment-form').removeClass('d-none');
        }
      },
      showMuteBlock() {
        let sid = this.status.account.id;
        let uid = this.user.id;
        if(sid != uid) {
          $('.post-actions').removeClass('d-none');
        }
      },
      reportUrl() {
        return '/i/report?type=post&id=' + this.status.id;
      },
      timestampFormat() {
          let ts = new Date(this.status.created_at);
          return ts.toDateString() + ' ' + ts.toLocaleTimeString();
      },
      fetchData() {
          let url = '/api/v2/profile/'+this.statusUsername+'/status/'+this.statusId;
          axios.get(url)
            .then(response => {
                let self = this;
                self.status = response.data.status;
                self.user = response.data.user;
                self.media = self.status.media_attachments;
                self.reactions = response.data.reactions;
                this.buildPresenter();
                this.showMuteBlock();
            }).catch(error => {
              if(!error.response) {
                $('.postPresenterLoader .lds-ring').attr('style','width:100%').addClass('pt-4 font-weight-bold text-muted').text('An error occured, cannot fetch media. Please try again later.');
              } else {
                switch(error.response.status) {
                  case 401:
                    $('.postPresenterLoader .lds-ring')
                      .attr('style','width:100%')
                      .addClass('pt-4 font-weight-bold text-muted')
                      .text('Please login to view.');
                  break;

                  default:
                  $('.postPresenterLoader .lds-ring').attr('style','width:100%').addClass('pt-4 font-weight-bold text-muted').text('An error occured, cannot fetch media. Please try again later.');
                  break;
                }
              }
            });
      },
      commentFocus() {
        $('.comment-form input[name="comment"]').focus();
      },
      buildPresenter() {
        let container = $('.postPresenterContainer');
        let status = this.status;
        let media = this.media;

        $('input[name="item"]').each(function(k, v) {
            let el = $(v);
            el.val(status.account.id);
        });

        $('.status-comment .comment-text').html(status.content);

        if(container.children().length != 0) {
          return;
        }
        switch(this.statusTemplate) {
          case 'image':
            pixelfed.presenter.show.image(container, media);
          break;

          case 'album':
            pixelfed.presenter.show.imageAlbum(container, media, this.status);
          break;

          case 'video':
            pixelfed.presenter.show.video(container, media);
          break;

          default:
              $('.postPresenterLoader .lds-ring').attr('style','width:100%').addClass('pt-4 font-weight-bold text-muted').text('An error occured, cannot fetch media. Please try again later.');
          break;

        }
        if(container.children().length == 0) {
          $('.postPresenterLoader .lds-ring').attr('style','width:100%').addClass('pt-4 font-weight-bold text-muted').text('An error occured, cannot fetch media. Please try again later.');
          return;
        }
        pixelfed.readmore();
        $('.postPresenterLoader').addClass('d-none');
        $('.postPresenterContainer').removeClass('d-none');
      },
    }
}
</script>