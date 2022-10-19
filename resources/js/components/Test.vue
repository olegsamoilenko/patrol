<template>
    <ul>
        <li v-for="item in items" :key="item">
            <slot name="item" v-bind="item"></slot>
        </li>
    </ul>
</template>

<script>
export default {
  name: "Test",
    props: {
        items: {
            type: Array,
            required: true
        },
        apiUrl: {
            type: String,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        }
    },
    data() {
    return {

    };
  },
}
</script>

<style scoped>

</style>
