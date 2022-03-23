<template>
    <div class="metric-card">
        <b-card class="text-center" :header-bg-variant="variant" header-text-variant="white" :border-variant="variant"  :header="title">
            <slot></slot>
        </b-card>
    </div>
</template>

<script>
    export default {
        props: [ 'variant', 'title' ],
        data: function () {
            return {};
        }
    };
</script>