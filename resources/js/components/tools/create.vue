<template>
    <div>
        <v-dialog v-model="active">
            <v-card>
                <v-card-title>¿Está usted seguro de guardar?/Confirm?</v-card-title>
                <v-card-actions>
                    <v-btn color="success" text @click.prevent="createTool">Guardar/Save</v-btn>
                    <v-btn color="error" text @click="active = false">Cancelar/Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar.active" :color="snackbar.color" :timeout="1500" > {{ snackbar.message }}</v-snackbar>
        <v-btn @click="active = true" :disabled="disabled" :loading="loading">Guardar/Save</v-btn>
        <v-form v-model="valid">
            <div class="form-container">
                <div class="form-column">
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.country" label="Pais/Country" :items="countrys" item-text="name" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.country" label="Pais/Country" :items="countrys" item-text="name" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.turn" label="Actividad/Activity" :items="turns" item-text="name" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.turn" label="Actividad/Activity" :items="turns" item-text="name" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.training" label="Servicios/Service" :items="trainings" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.training" label="Servicios/Service" :items="trainings" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.distribution" label="Distribucion/Distribution" :items="distributions" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.distribution" label="Distribucion/Distribution" :items="distributions" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                    <div class="form-row">
                        <v-combobox v-if="verifyAccess([1])" v-model.trim="tool.training" label="Capacitacion/Training" :items="trainings" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-combobox>
                        <v-select v-else v-model.trim="tool.training" label="Capacitacion/Training" :items="trainings" item-text="name" :rules="[rules.required]" clearable item-value="name"></v-select>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-row">
                        <v-text-field v-model="tool.tradename" label="Nombre Comercial/Company Name" :rules="[rules.required]"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.fvn" label="Razon Social/Business Name" :rules="[rules.required]"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.address" label="Direccion/Address" :rules="[rules.required]"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.contact" label="Contacto de Compras/Purchasing Contact"></v-text-field>
                    </div>
                   <div class="form-row">
                        <v-text-field v-model="tool.phone" label="Telefono Oficina/Office Phone" :rules="[rules.required]"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.phone1" label="Telefono Celular/Cell Phone" :rules="[rules.required]"></v-text-field>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-row">
                        <v-text-field v-model="tool.mail" label="Correo/Email" :rules="[rules.required]"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.payments" label="Contacto de Pagos/Payment Contact" :rules="[rules.required]"></v-text-field>
                    </div>
                     <div class="form-row">
                        <v-text-field v-model="tool.phonee" label="Telefono Oficina/Office Phone" :rules="[rules.required]"></v-text-field>
                    </div>
                    <div class="form-row">
                        <v-text-field v-model="tool.phonee2" label="Telefono Celular/Cell Phone" :rules="[rules.required]"></v-text-field>
                    </div>
                     <div class="form-row">
                        <v-text-field v-model="tool.maill" label="Correo/Email" :rules="[rules.required]"></v-text-field>
                    </div>
                     <div class="form-row">
                        <v-text-field v-model="tool.terms" label="Condiciones de Venta/Sales Arrangement" :rules="[rules.required]"></v-text-field>
                    </div>
                     <div class="form-row">
                        <v-text-field v-model="tool.credit" label="Credito/Credit" :rules="[rules.required]"></v-text-field>
                    </div>
                </div>
            </div>
        </v-form>
    </div>
</template>

<script>
import { getToken, verifyAccess } from "../../lib/auth";
import { required } from "../../lib/rules";
import vueFilePond, { setOptions } from "vue-filepond";
import "filepond/dist/filepond.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
const FilePond = vueFilePond(FilePondPluginFileValidateType);

export default {
    name: "create",
    data: () => ({
        snackbar: { active: false, message: null, color: 'success' },
        active: false,
        loading: true,
        menu: false,
        valid: false,
        rules : { required: required },
        countrys: [],
        turns: [],
        servicess: [],
        distributions: [],
        trainings: [],
        tool: {
            country: null,
            turn: null,
            services: null,
            distribution: null,
            training: null,
             tradename: null,
            bname: null,
            fvn: null,
            address: null,
            contact: null,
            phone: null,
            phone1: null,
            mail: null,
            payments: null,
            phonee: null,
            phonee2: null,
            maill: null,
            terms: null,
            credit: null
        }
    }),
    methods: {
        verifyAccess(roles) {
            return verifyAccess(roles)
        },
        async onProcessFile(error, file) {
            if (error === null) {
                this.tool.documents.push(file.serverId)
            }
        },
        async createTool() {
            this.active = false
            this.loading = true
            const response = await axios.post('/api/tools', this.tool, getToken())
            if (response.status === 200) {
                this.snackbar = {
                    active: true,
                    message: 'Herramienta registrada',
                    color: 'success'
                }
                this.clearForm()
            } else {
                this.snackbar = {
                    active: true,
                    message: 'Error al registrar',
                    color: 'error'
                }
            }
            this.loading = false
        },
        clearForm() {
            this.tool = {
                country: null,
                turn: null,
                services: null,
                distribution: null,
                training: null,
                tradename: null,
                bname: null,
                fvn: null,
                address: null,
                contact: null,
                phone: null,
                phone1: null,
                mail: null,
                payments: null,
                phonee: null,
                phonee2: null,
                maill: null,
                terms: null,
                credit: null
            }
            this.$refs.documents.removeFiles()
        }
    },
    computed: {
        disabled() {
            if (this.tool.hasValidation && this.tool.calibrationExpiration === null) {
                return true
            }
            return !this.valid
        }
    },
    async created() {
        setOptions({
            server: {
                url: '/api/uploads',
                withCredentials: true,
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            }
        })
        await axios.get('/api/countrys', getToken()).then(response => this.countrys =  response.data )
        await axios.get('/api/turns', getToken()).then(response => this.turns =  response.data )
        await axios.get('/api/servicess', getToken()).then(response => this.servicess = response.data)
        await axios.get('/api/distributions', getToken()).then(response => this.distributions = response.data)
        await axios.get('/api/trainings', getToken()).then(response => this.trainings = response.data)
        this.loading = false
    },
    components: {
        FilePond
    }

}
</script>

<style scoped>
.form-container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1rem;
}
.form-row {
    margin: 1rem 0;
}
</style>
