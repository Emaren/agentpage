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
      background: '#95b8a4',
      color: '#ffffff'
    },
    tweets: {
      background: '#f5f5f5',
      color: '#050505',
      links: '#4aed05'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('VernMooreC21').start();
</script>