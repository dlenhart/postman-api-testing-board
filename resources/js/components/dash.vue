<template>
  <div>
    <b-row>
      <b-col lg="6" class="my-1">
        <b-button size="sm" variant="success" @click="reload">
          Refresh <i v-if="!loading" class="fa fa-refresh"></i>
          <b-spinner v-if="loading" small type="grow"></b-spinner>
        </b-button>

        <b-button size="sm" variant="primary" v-b-modal.modal-1>
          Help <i class="fa fa-question-circle"></i>
        </b-button>

        <b-dropdown
          size="sm"
          id="dropdown-left"
          text="View"
          variant="outline-primary"
          class=""
        >
          <b-dropdown-item v-bind:href="homeLink()">All</b-dropdown-item>
          <b-dropdown-divider></b-dropdown-divider>
          <b-dropdown-item
            v-for="dropdown in dropdowns"
            :key="dropdown.id"
            v-bind:href="dropDownLinks(dropdown)"
          >
            {{ dropdown.name }}
          </b-dropdown-item>
        </b-dropdown>

        <b-button v-if="applicationPage" size="sm" variant="outline-dark">{{
          application.toUpperCase()
        }}</b-button>
      </b-col>
      <b-col lg="6" class="my-1">
        <b-input-group size="sm">
          <b-form-input
            id="filter-input"
            v-model="filter"
            type="search"
            placeholder="Search"
          ></b-form-input>

          <b-input-group-append>
            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
          </b-input-group-append>
        </b-input-group>
      </b-col>
    </b-row>
    <br />
    <stats-component
      v-if="!applicationPage"
      :stats="stats"
      :statsLoading="statsLoading"
      :statsError="statsError"
      :app="application"
    ></stats-component>

    <card-component
      v-if="applicationPage"
      :stats="stats"
      :statsLoading="statsLoading"
      :statsError="statsError"
      :app="application"
    ></card-component>
    <br />
    <b-table
      :items="items"
      :fields="computedFields"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :filter="filter"
      empty-filtered-text="There are no records matching your request!"
      empty-text="There are no records matching your request!"
      striped
      responsive="sm"
    >
      <template #cell(Branch)="row">
        <strong>{{ row.item.branch }}</strong>
      </template>

      <template #cell(Duration)="row"> {{ row.item.duration }} ms </template>

      <template #cell(Iterations)="row">
        <div class="text-center">
          <b-badge
            pill
            :variant="row.item.iterations_failed >= 1 ? 'danger' : 'success'"
            >{{ row.item.iterations_failed }} /
            {{ row.item.iterations_total }}</b-badge
          >
        </div>
      </template>

      <template #cell(Requests)="row">
        <div class="text-center">
          <b-badge
            pill
            :variant="row.item.requests_failed >= 1 ? 'danger' : 'success'"
            >{{ row.item.requests_failed }} /
            {{ row.item.requests_total }}</b-badge
          >
        </div>
      </template>

      <template #cell(Assertions)="row">
        <div class="text-center">
          <b-badge
            pill
            :variant="row.item.assertions_failed >= 1 ? 'danger' : 'success'"
            >{{ row.item.assertions_failed }} /
            {{ row.item.assertions_total }}</b-badge
          >
        </div>
      </template>

      <template #cell(show_details)="row">
        <div class="text-center">
          <b-button pill size="sm" @click="row.toggleDetails" class="mr-2">
            {{ row.detailsShowing ? "Hide" : "Show" }}
            <i
              v-bind:class="{
                'fa fa-chevron-up': row.detailsShowing,
                'fa fa-chevron-down': !row.detailsShowing,
              }"
              aria-hidden="true"
            ></i>
          </b-button>
        </div>
      </template>

      <template #cell(Timestamp)="row">
        <b-button
          v-b-popover.hover.right="row.item.created_at"
          variant="light"
          >{{ row.item.created_at | formatDate }}</b-button
        >
      </template>

      <template #cell(secretActions)="row">
        <div class="text-center">
          <b-button
            pill
            size=""
            variant="light"
            @click="sendInfo(row.item.id)"
            v-b-modal.modal-delete
          >
            <i class="fa fa-trash"></i>
          </b-button>
        </div>
      </template>

      <template #row-details="row">
        <b-card>
          <b-row class="mb-2">
            <b-col sm="3" class="text-sm-left"><b>Average Response:</b></b-col>
            <b-col>{{ row.item.responseAverage }} ms</b-col>

            <b-col sm="2" class="text-sm-left"><b>Min:</b></b-col>
            <b-col>{{ row.item.responseMin }} ms</b-col>

            <b-col sm="2" class="text-sm-left"><b>Max:</b></b-col>
            <b-col>{{ row.item.responseMax }} ms</b-col>
          </b-row>

          <b-row class="mb-2">
            <b-col sm="12" class="text-sm-left">
              <div class="terminal">
                <p v-html="row.item.parsed_results"></p>
              </div>
            </b-col>
          </b-row>
        </b-card>
      </template>
    </b-table>
    <pagination :data="data" @pagination-change-page="getResults"></pagination>

    <!--upload modal-->
    <b-modal id="modal-1" size="lg" title="Help">
      <b-row>
        <b-col sm="3">
          <strong>Assertions</strong>
        </b-col>
        <b-col sm="9">
          The total number of test cases.<br />
          <b-badge pill variant="success">0 / 51</b-badge>
          <em class="text-small"
            >Example states 0 failures out of 51 assertions ( passing )</em
          ><br />
          <b-badge pill variant="danger">9 / 51</b-badge>
          <em class="text-small"
            >Example states 9 failures out of 51 assertions ( failures )</em
          ><br />
        </b-col>
      </b-row>
      <hr />
      <b-row>
        <b-col sm="3">
          <strong>Iterations</strong>
        </b-col>
        <b-col sm="9">
          The total number of times the suite has ran.<br />
          <b-badge pill variant="success">0 / 1</b-badge>
          <em class="text-small"
            >Example states 0 failures out of 1 iteration.</em
          ><br />
          <b-badge pill variant="danger">1 / 1</b-badge>
          <em class="text-small"
            >Example states 1 failure out of 2 iterations.</em
          ><br />
        </b-col>
      </b-row>
    </b-modal>

    <!--delete modal-->
    <b-modal hide-footer id="modal-delete" title="Delete">
      Are you sure you want to delete this record?

      <b-button
        class="mt-3"
        variant="outline-danger"
        block
        @click="deleteRecord(modalItem)"
        >Delete</b-button
      >
      <b-button
        class="mt-1"
        variant="outline-primary"
        block
        @click="$bvModal.hide('modal-delete')"
        >Cancel</b-button
      >
    </b-modal>
  </div>
</template>

<script>
export default {
  props: {
    view_type: String,
    application: String,
    isUserAdmin: Boolean,
  },

  computed: {
    computedFields() {
      if (!this.isUserAdmin)
        return this.fields.filter((field) => !field.requiresAdmin);
      else return this.fields;
    },
  },

  data() {
    return {
      loading: true,
      statsLoading: true,
      error: false,
      statsError: false,
      sortBy: "",
      search: "",
      filter: null,
      filterOn: ["Apps", "Branch"],
      sortDesc: false,
      url: "",
      file1: null,
      applicationPage: false,
      fields: [
        {
          label: "App",
          key: "application.name",
          class: "boldme",
          sortable: true,
        },
        { label: "Branch", key: "branch", class: "boldme", sortable: true },
        { key: "Assertions", sortable: false },
        { key: "Iterations", sortable: false },
        { key: "Requests", sortable: false },
        { key: "Duration", sortable: false },
        { key: "show_details", sortable: false },
        { key: "Timestamp", sortable: false },
        { key: "secretActions", label: "Actions", requiresAdmin: true },
      ],
      items: [],
      stats: [],
      modalItem: "",
      data: {},
      dropdowns: [],
      results: "",
    };
  },

  created() {
    if (this.application != "null") {
      this.applicationPage = true;
    }

    this.getResults();
    this.getDropdownItems();
  },

  mounted() {
    if (this.applicationPage) {
      this.getTotals();
    } else {
      this.getHolisticTotals();
    }
  },

  methods: {
    dropDownLinks(app) {
      return route("app.view", { app: app.name });
    },

    homeLink() {
      return route("dashboard.view");
    },

    deleteRecord(id) {
      const params = {
        id: id,
      };

      axios
        .post(route("results.delete"), params)
        .then((response) => {
          if (response.status === 200) {
            this.items = this.items.filter((item) => item.id != id);
            this.$bvModal.hide("modal-delete");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    sendInfo(item) {
      this.modalItem = item;
    },

    delayedLoading() {
      // We like to see spinners, sometimes the page
      // loads so fast, we dont get to see spinners!!
      setTimeout(() => (this.loading = false), 1000);
    },

    getResults(page) {
      this.loading = true;

      if (typeof page === "undefined") {
        page = 1;
      }

      if (this.view_type == "app") {
        this.url = route("application", { app: this.application, page: page });
      } else {
        this.url = route("results", { page: page });
      }

      axios
        .get(this.url)
        .then((response) => {
          if (response.status === 200) {
            this.items = response.data.data;
            this.data = response.data;
            this.delayedLoading();
          }
        })
        .catch((error) => {
          this.delayedLoading();
          this.error = true;
        });
    },

    getDropdownItems() {
      axios
        .get(route("app.list"))
        .then((response) => {
          if (response.status === 200) {
            this.dropdowns = response.data.data;
          }
        })
        .catch((error) => {
          console.log("Error pulling list");
        });
    },

    getTotals() {
      axios
        .get(route("stats", { app: this.application }))
        .then((response) => {
          if (response.status === 200) {
            this.stats = response.data;
            this.statsLoading = false;
          }
        })
        .catch((error) => {
          this.statsLoading = false;
          this.statsError = true;
          console.log(error);
        });
    },

    getHolisticTotals() {
      axios
        .get(route("stats.holistic"))
        .then((response) => {
          if (response.status === 200) {
            this.stats = response.data;
            this.statsLoading = false;
            console.log(this.stats);
          }
        })
        .catch((error) => {
          this.statsLoading = false;
          this.statsError = true;
          console.log(error);
        });
    },

    reload() {
      this.items = [];
      this.data = {};
      this.getResults();
      this.getDropdownItems();

      if (this.applicationPage) {
        this.getTotals();
      } else {
        this.getHolisticTotals();
      }
    },
  },
};
</script>
