<template>
    <div>
        <b-row>
        <b-col lg="3" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Total Applications">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-lg">
                {{ stats.total_apps_tested }}
                <b-icon-exclamation-triangle-fill v-if="statsError" class="text-primary"></b-icon-exclamation-triangle-fill>
            </span>
        </metric-card>
        </b-col>
        <b-col lg="3" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Tests in 24 hours">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-lg">
                {{ stats.twenty_four_hours }}
                <b-icon-exclamation-triangle-fill v-if="statsError" class="text-primary"></b-icon-exclamation-triangle-fill>
            </span>
        </metric-card>
        </b-col>
        <b-col lg="3" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Passing Dev (24hrs)">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-lg">
                {{ stats.development }}%
                <b-icon-exclamation-triangle-fill v-if="statsError" class="text-primary"></b-icon-exclamation-triangle-fill>
            </span>
        </metric-card>
        </b-col>
        <b-col lg="3" class="my-1">
        <metric-card v-cloak
                variant="dark"
                title="Passing Master (24hrs)">
            <b-spinner v-if="statsLoading" style="width: 4rem; height: 4rem;" label="Large Spinner"></b-spinner>
            <span class="text-lg">
                {{ stats.master }}%
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
