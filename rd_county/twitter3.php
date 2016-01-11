<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 1,
  interval: 6000,
  width: 317,
  height: 76,
  theme: {
    shell: {
      background: '#962496',
      color: '#050305'
    },
    tweets: {
      background: '#fcfcfc',
      color: '#030203',
      links: '#eb076e'
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
}).render().setUser('ShylaP_Realtor').start();
</script>