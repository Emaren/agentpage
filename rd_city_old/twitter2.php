<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 204,
  height: 76,
  theme: {
    shell: {
      background: '#358dbd',
      color: '#140c14'
    },
    tweets: {
      background: '#faf5fa',
      color: '#0a070a',
      links: '#eb0713'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setUser('<? echo $realtr['twitter_account'];?>').start();
</script>