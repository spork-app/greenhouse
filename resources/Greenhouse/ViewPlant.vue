<template>
    <div>
        {{ plant }}
    </div>
</template>

<script>
export default {
    computed: {
        plant() {
            return this.$store.getters.plants?.find(plant => plant.id == this.$route.params.id)
        },
    },
    watch: {
        plant(oldValue, newValue) {
            if (!newValue) {
                this.$store.dispatch('getPlants')
            }
        },
    },
    mounted() {
        this.$store.dispatch('getPlants');
    }
}
</script>