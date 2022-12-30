export default {
    namespaced: true,
    state: {
        items: [],
        filters: {
            country: { text: 'Pais', value: 'country.name' ,active: true, key: 'country' },
            turn: { text: 'Giro de la empresa', value: 'turn.name' ,active: true, key: 'turn' },
            distribution: { text: 'Distribucion', value: 'distribution.name', active: true, key: 'distribution' },
            services: { text: 'Servicios', value: 'services.name', active: true, key: 'services' },
            training: { text: 'Capacitacion', value: 'training.name', active: true, key: 'training' },
            tradename: { text: 'Nombre comercial', value: 'tradename', active: false, key: 'tradename' },
            fvn: { text: 'RFC/VAT/NIF', value: 'fvn', active: false, key: 'fvn' },
            address: { text: 'Direccion completa', value: 'address', active: false, key: 'address' },
            contact: { text: 'Contacto compras', value: 'contact', active: false, key: 'contact' },
            phone: { text: 'Telefono', value: 'phone', active: false, key: 'phone' },
            mail: { text: 'Correo', value: 'mail', active: false, key: 'mail' },
            payments: { text: 'Contacto pagos', value: 'payments', active: false, key: 'payments' },
            phonee: { text: 'Telefono', value: 'phonee', active: false, key: 'phonee' },
            maill: { text: 'Correo', value: 'maill', active: false, key: 'maill' },
            terms: { text: 'Condiciones de venta', value: 'terms', active: false, key: 'terms' },
            credit: { text: 'Credito', value: 'credit', active: false, key: 'credit' },
            item: { text: 'Item', value: 'item', active: false, key: 'item' },
            user: { text: 'Usuario', value: 'user.name', active: false, key: 'user_id' },
        },
        historyMode: false,
        historyItems: [],
    },
    mutations: {
        setFilters(state, { filters }) {
            state.filters = filters
        },
        setItems(state, { items }) {
            state.items = items
        },
        setHistoryMode(state, { value }) {
            state.historyMode = value
        },
        setHistoryItems(state, { items }) {
            state.historyItems = items
        }
    },
    actions: {
        setHistoryItems({ commit }, { items }) {
            commit('setHistoryItems', { items })
        },
        setHistoryMode({ commit }, { value }) {
            commit('setHistoryMode', { value })
        },
        setFilters({ commit }, { filters }) {
            commit('setFilters', { filters })
        },
        setItems({ commit }, { items }) {
            commit('setItems', { items })
        }
    },
    getters: {
        historyItems: state => {
            return state.historyItems
        },
        historyMode: state => {
            return state.historyMode
        },
        activeFilters: state => {
            const activeFiltersKeys = Array.from(Object.keys(state.filters)).filter(key => state.filters[key].active)
            return activeFiltersKeys.map(key => state.filters[key])
        },
        filters: state => {
            return JSON.parse(JSON.stringify(state.filters))
        },
        items: state => {
            return state.items
        }
    }
}
