<template>
    <v-expansion-panels v-model="panel">
        <v-expansion-panel>
            <v-expansion-panel-header>Filtros/Filters</v-expansion-panel-header>
            <v-expansion-panel-content>
                <v-row>
                    <v-col>
                        <active-filters />
                    </v-col>
                    <v-col>
                        <v-btn color="success" text @click.prevent="search">Aplicar filtros/Apply field <v-icon>mdi-download</v-icon></v-btn>
                    </v-col>
                    <v-col>
                        <v-btn color="cyan" text @click.prevent="history">Consultar Historial/History <v-icon>mdi-history</v-icon></v-btn>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="4" v-if="filters.country.active"><v-select v-model="filter.country" label="Pais" :items="countrys" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.turn.active"><v-select v-model="filter.turn" label="Giro de la empresa" :items="turns" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.distribution.active"><v-select v-model="filter.distribution" label="Distribucion" :items="distribution" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.services.active"><v-select v-model="filter.services" label="Servicios" :items="servicess" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.training.active"><v-select v-model="filter.training" label="Capacitacion" :items="trainings" item-text="name" return-object clearable></v-select></v-col>
                    
                    <v-col cols="4" v-if="filters.mainLocalization.active"><v-text-field v-model="filter.mainLocalization" label="Localizacion principal" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.shelfLocalization.active"><v-text-field v-model="filter.shelfLocalization" label="Localizacion de estante" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.shelf.active"><v-text-field v-model="filter.shelf" label="Estante" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.measurement.active"><v-text-field v-model="filter.measurement" label="Medida" clearable></v-text-field></v-col>
                   
                    <v-col cols="4" v-if="filters.minStock.active"><v-text-field v-model="filter.minStock" label="Inventario minimo" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.quantity.active"><v-text-field v-model.number="filter.quantity" label="Cantidad" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.serialNumber.active"><v-text-field v-model="filter.serialNumber" label="Serie" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.item.active"><v-text-field v-model="filter.item" label="Item" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.user.active"><v-select v-model="filter.user" label="Usuario/User" :items="users" item-text="email" return-object clearable></v-select></v-col>
                    
                </v-row>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>

<script>
import { getToken } from "../../lib/auth";
import activeFilters from "./activeFilters";
import { mapGetters } from "vuex"

export default {
    name: "filters",
    data: () => ({
        panel: 0,
        countrys: [{id: 0, name: 'TODOS'}],
        turns: [{id: 0, name: 'TODOS'}],
        distributions: [{id: 0, name: 'TODOS'}],
        servicess: [{id: 0, name: 'TODOS'}],
        trainings: [{id: 0, name: 'TODOS'}],
        users: [{id: 0, email: 'TODOS'}],
        menu: false,
        filter: {
            country: null,
            turn: null,
            distribution: null,
            services: null,
            training: null,
            hasValidation: false,
            mainLocalization: null,
            shelfLocalization: null,
            shelf: null,
            measurement: null,
            dispatchable: false,
            minStock: 0,
            quantity: 0,
            model: null,
            serialNumber: null,
            item: null,
            user: null,
            calibrationExpiration: null,
        },
        historyHeaders: [
            {text: 'Item', value: 'tool.item'},
            {text: 'Servicios', value: 'services.name'},
            {text: 'Fecha', value: 'created_at'},
            {text: 'Ejecutor', value: 'user.email'},
            {text: 'Actividad', value: 'comment'},
            {text: 'Informacion Actual', value: 'after'},
            {text: 'Informacion Anterior', value: 'before'}
        ]
    }),
    methods: {
        async history() {
            await this.$store.dispatch('filters/setHistoryMode', { value: true })
            this.$emit('loading', true)
            const response = await axios.get('/api/history', getToken())
            if (response.status === 200) {
                const items = response.data.map(item => {
                    return {
                        ...item,
                        before: JSON.parse(item.before),
                        after: JSON.parse(item.after)
                    }
                })
                await this.$store.dispatch('filters/setHistoryItems', { items })
            }
            this.$emit('loading', false)
        },
        async search() {
            this.$emit('loading', true)
            await this.$store.dispatch('filters/setHistoryMode', { value: false })
            const query = {}
            const activeFilters = Object.keys(this.filters).filter(filter => this.filters[filter].active)
            for (let key of activeFilters) {
                query[key] = this.filter[key]
            }
            const response = await axios.post('/api/search', query, getToken())
            if (response.status === 200) {
                await this.$store.dispatch('filters/setItems', { items: response.data })
            }
            this.$emit('loading', false)
        }
    },
    computed: {
        ...mapGetters('filters', ['filters','activeFilters'])
    },
    created() {
        axios.get('/api/countrys', getToken())
            .then(response => {
                this.countrys = this.countrys.concat(response.data)
                this.filter.country = this.groups[0]
            })
        axios.get('/api/turns', getToken())
            .then(response => {
                this.turns = this.turns.concat(response.data)
                this.filter.turn = this.turns[0]
            })
        axios.get('/api/distributions', getToken())
            .then(response => {
                this.distributions = this.distributions.concat(response.data)
                this.filter.distribution = this.distributions[0]
            })
        axios.get('/api/servicess', getToken())
            .then(response => {
                this.servicess = this.servicess.concat(response.data)
                this.filter.services = this.servicess[0]
            })
        axios.get('/api/trainings', getToken())
            .then(response => {
                this.trainings = this.trainings.concat(response.data)
                this.filter.training = this.trainings[0]
            })
        axios.get('/api/users', getToken())
          .then(response => {
              this.users = this.users.concat(response.data)
          })
    },
    components: {
        activeFilters
    }

}
</script>

<style scoped>

</style>
