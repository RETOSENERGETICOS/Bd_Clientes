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
                    <v-col cols="4" v-if="filters.country.active"><v-select v-model="filter.country" label="Pais/Country" :items="countrys" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.turn.active"><v-select v-model="filter.turn" label="Actividad/Activity" :items="turns" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.distribution.active"><v-select v-model="filter.distribution" label="Distribucion/Distribution" :items="distribution" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.services.active"><v-select v-model="filter.services" label="Servicios/Service" :items="servicess" item-text="name" return-object clearable></v-select></v-col>
                    <v-col cols="4" v-if="filters.training.active"><v-select v-model="filter.training" label="Capacitacion/Training" :items="trainings" item-text="name" return-object clearable></v-select></v-col>
                    
                    <v-col cols="4" v-if="filters.tradename.active"><v-text-field v-model="filter.tradename" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.bname.active"><v-text-field v-model="filter.bname" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.fvn.active"><v-text-field v-model="filter.fvn" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.address.active"><v-text-field v-model="filter.address" label="Nombre comercial" clearable></v-text-field></v-col>
                   
                    <v-col cols="4" v-if="filters.contact.active"><v-text-field v-model="filter.contact" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.phone.active"><v-text-field v-model="filter.phone" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.mail.active"><v-text-field v-model="filter.mail" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.payments.active"><v-text-field v-model="filter.payments" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.phonee.active"><v-text-field v-model="filter.phonee" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.maill.active"><v-text-field v-model="filter.maill" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.terms.active"><v-text-field v-model="filter.terms" label="Nombre comercial" clearable></v-text-field></v-col>
                    <v-col cols="4" v-if="filters.credit.active"><v-text-field v-model="filter.credit" label="Nombre comercial" clearable></v-text-field></v-col>
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
            tradename: null,
            bname: null,
            fvn: null,
            address: null,
            contact: null,
            phone: null,
            mail: null,
            payments: null,
            phonee: null,
            maill: null,
            terms:null,
            credit: null,
            item: null,
            user: null,
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
