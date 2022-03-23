<template>
  <div>
      something something
  </div>
</template>

<script>
export default {
  data() {
    return {
    }
  },

  methods: {
    delayedLoading()
    {
      setTimeout(() => this.loading = false, 1000);
    },

    getResults(page)
    {
      this.loading = true

      if (typeof page === 'undefined') {
          page = 1;
      }

      axios.get( '/api/results?page=' + page )
        .then( response => {
          if ( response.status === 200 ) {
            this.items = response.data.data;
            this.data = response.data
            this.delayedLoading()
          }
        } ).catch( error => {
          this.loading = false;
          this.error = true;
        } );
    },

    reload() {
      this.getResults(); 
    },
  }
}
</script>
