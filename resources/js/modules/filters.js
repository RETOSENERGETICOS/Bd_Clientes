export default {
    namespaced: true,
    state: {
        items: [],
        filters: {
            country: { text: 'Pais', value: 'country.name' ,active: true, key: 'country' },
            turn: { text: 'Actividad', value: 'turn.name' ,active: true, key: 'turn' },
            distribution: { text: 'Distribucion', value: 'distribution.name', active: true, key: 'distribution' },
            services: { text: 'Servicios', value: 'services.name', active: true, key: 'services' },
            training: { text: 'Capacitacion', value: 'training.name', active: true, key: 'training' },
            tradename: { text: 'Nombre Comercial', value: 'tradename', active: false, key: 'tradename' },
            bname: { text: 'Razon social', value: 'bname', active: false, key: 'bname' },
            fvn: { text: 'Razon Social/Business Name', value: 'fvn', active: false, key: 'fvn' },
            address: { text: 'Direccion', value: 'address', active: false, key: 'address' },
            contact: { text: 'Contacto Compras', value: 'contact', active: false, key: 'contact' },
            phone: { text: 'Telefono Oficina', value: 'phone', active: false, key: 'phone' },
            phone1: { text: 'Telefono Celular', value: 'phone1', active: false, key: 'phone1' },
            mail: { text: 'Correo', value: 'mail', active: false, key: 'mail' },
            payments: { text: 'Contacto Pagos', value: 'payments', active: false, key: 'payments' },
            phonee: { text: 'Telefono Oficina', value: 'phonee', active: false, key: 'phonee' },
            phonee2: { text: 'Telefono Celular', value: 'phonee2', active: false, key: 'phonee2' },
            maill: { text: 'Correo', value: 'maill', active: false, key: 'maill' },
            terms: { text: 'Condiciones de Venta', value: 'terms', active: false, key: 'terms' },
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
