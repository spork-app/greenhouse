export default {
    state: {
        plants: [],
        plantPaginator: {},
        seeds: [],
        seedPaginator: {},
        errors: {},
        actions: []
    },
    getters: {
        greenhouseErrors: state => state.errors,
        plants: state => state.plants,
        seeds: state => state.seeds,
        plantPaginator: state => state.plantPaginator,
        seedPaginator: state => state.seedPaginator,
    },
    actions: {
        async getPlants({ state }, { page, limit } = { page: 1, limit: 15 }) {
            const { data: { data, ...pagination } } = await axios.get(buildUrl('/api/greenhouse/plants', { page, limit }));
            
            state.plants = data;
            state.plantPaginator = pagination;
        },
        async getSeeds({ state }, { page, limit } = { page: 1, limit: 15 }) {
            const { data: { data, ...pagination } } = await axios.get(buildUrl('/api/greenhouse/seeds', { page, limit }));

            state.seeds = data;
            state.seedPaginator = pagination;
        },
        async createPlant({ state }, plant) {
            const { data } = await axios.post('/api/greenhouse/plants', plant);
            state.plants.push(data);
            Spork.toast('Created ' + plant.name + ' successfully');
        },
        async createSeeds({ state }, seed) {
            const { data } = await axios.post('/api/greenhouse/seeds', seed);
            state.seeds.push(data);
            Spork.toast('Created ' + seed.name + ' successfully');
        },
        async deletePlant({ state }, plant) {
            await axios.delete(`/api/greenhouse/plants/${plant.id}`);

            state.plants = state.plants.filter(p => p.id !== plant.id);
        },
        async deleteSeed({ state }, seed) {
            await axios.delete(`/api/greenhouse/seeds/${seed.id}`);

            state.seeds = state.seeds.filter(p => p.id !== seed.id);
        },
        async updatePlant({ state }, plant) {
            const { data } = await axios.put(`/api/greenhouse/plants/${plant.id}`, plant);

            state.plants = state.plants.map(p => p.id === data.id ? data : p);
        },
        async updateSeed({ state }, seed) {
            const { data } = await axios.put(`/api/greenhouse/seeds/${seed.id}`, seed);

            state.seeds = state.seeds.map(p => p.id === data.id ? data : p);
        },
        async fetchPlantSeedLogs({ dispatch }) {
            dispatch('fetchLogs', {
                filter: {
                    subject_type: 'Spork\\Greenhouse\\Models\\Plant,Spork\\Greenhouse\\Models\\Seed'
                },
                include: 'causer,subject',
                sort: '-created_at',
                page: 1,
                limit: 100,
            })
        }
    }
}