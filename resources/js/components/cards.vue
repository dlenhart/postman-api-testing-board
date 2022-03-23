<template>
    <div>
        <b-row>
        <b-col lg="4" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Total Tests Run">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-xl">
                {{ stats.total }}
                <b-icon-exclamation-triangle-fill v-if="statsError" class="text-primary"></b-icon-exclamation-triangle-fill>
            </span>
        </metric-card>
        </b-col>
        <b-col lg="4" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Total Failures">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-xl">
                {{ stats.failures }}
                <b-icon-exclamation-triangle-fill v-if="statsError" class="text-primary"></b-icon-exclamation-triangle-fill>
            </span>
        </metric-card>
        </b-col>
        <b-col lg="4" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Total Percent Pass">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-xl">
                {{ stats.percent }}%
                <b-icon-exclamation-triangle-fill v-if="statsError" class="text-primary"></b-icon-exclamation-triangle-fill>
            </span>
        </metric-card>
        </b-col>
        </b-row>
    </div>
</template>
<script>
export default {
  props: { 
    app: String,
    stats: [],
    statsLoading: Boolean,
    statsError: Boolean
  },

  data() {
    return {
      loading: false,
      error: false,
      totals: [],
      url: ''
    }
  },
}
</script>
