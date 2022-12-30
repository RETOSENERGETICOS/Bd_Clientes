<template>
    <div>
        <v-dialog v-model="active">
            <v-card>
                <v-card-title>¿Está usted seguro de guardar?/Confirm?</v-card-title>
                <v-card-actions>
                    <v-btn color="success" text @click.prevent="update">Guardar/Save</v-btn>
                    <v-btn color="error" text @click="active = false">Cancelar/Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="show" v-if="tool !== null" scrollable>
            <v-card>
                <v-card-title>Herramienta {{ tool.item }}</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form v-model="valid">
                        <v-row>
                            <v-col cols="4">
                                <v-combobox label="Pais" v-model="tool.country" item-text="name" :items="countrys" clearable item-value="name"></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-combobox label="Giro de la empresa" v-model="tool.turn" item-text="name" :items="turns" clearable item-value="name"></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-combobox label="Servicios" v-model="tool.services" item-text="name" :items="servicess" :rules="[rules.required]" clearable item-value="name" disabled></v-combobox>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4">
                                <v-combobox label="Distribucion" v-model="tool.distribution" item-text="name" :items="distributions" item-value="name" disabled></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-combobox label="Capacitacion" v-model="tool.training" item-text="name" :items="trainings" item-value="name" disabled></v-combobox>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Nombre comercial/Company Name" v-model="tool.tradename" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field label="RFC/VAT/NIF" v-model="tool.fvn" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Direccion/Address" v-model="tool.address" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Contacto de compras/Purchasing Contact" v-model="tool.contact"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field label="Telefono/Phone number" v-model="tool.phone" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Correo/Email" v-model="tool.mail" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Contacto de pagos/Payment Contact" v-model="tool.payments" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                           <v-col cols="4">
                                <v-text-field label="Telefono/Phone number" v-model="tool.phonee" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Correo/Email" v-model="tool.maill" :rules="[rules.required]"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field label="Condiciones de venta" v-model="tool.terms" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field label="Credito" v-model="tool.credit" :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text color="success" @click="active = true">Guardar/Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { getToken } from "../../lib/auth";
import { required } from "../../lib/rules";
import vueFilePond, { setOptions } from "vue-filepond";
import "filepond/dist/filepond.min.css"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
const FilePond = vueFilePond(FilePondPluginFileValidateType);

export default {
    name: "show",
    data: () => ({
        active: false,
        loading: false,
        valid: false,
        show: false,
        tool: null,
        movingQuantity: 0,
        menu: false,
        rules : { required: required },
        countrys: [],
        turns: [],
        servicess: [],
        distributions: [],
        trainings: [],
    }),
    methods: {
        async update() {
            this.active = false
            this.tool = { ...this.tool, movingQuantity: this.movingQuantity }
            const response = await axios.put(`/api/tools/${this.tool.id}`, this.tool, getToken())
            if (response.status === 200) {
                const newItem = {
                    id: this.tool.id,
                    item: this.tool.item,
                    country: this.tool.country,
                    measurement: this.tool.measurement,
                    turn: this.tool.turn,
                    services: this.tool.services,
                    distribution: this.tool.distribution,
                    training: this.tool.training,
                    serial_number: this.tool.serial_number,
                    calibration_expiration: this.tool.calibration_expiration,
                    has_validation: this.tool.has_validation
                }
                this.$emit('updated', newItem)
                this.show = false
                this.movingQuantity = 0
            }

        },
        async open(tool) {
            const response = await axios.get(`/api/tools/${tool.id}`, getToken())
            this.tool = response.data
            this.show = true
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
    async mounted() {
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
