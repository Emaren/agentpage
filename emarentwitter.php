<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 2,
  interval: 6000,
  width: 395,
  height: 6,
  theme: {
    shell: {
      background: '#470105',
      color: '#fcfcfc'
    },
    tweets: {
      background: '#e0e0e0',
      color: '#050505',
      links: '#1a0475'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('emaren').start();
</script>