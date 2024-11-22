<template>
    <div class="mt-5">
        <div class="row mb-2 mb-xl-3">
            <div class="col-md-auto">
                <h3><strong>Equipos</strong></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-5 col-md-6">
                <button class="btn btn-primary mb-3" @click="showModal(0)">
                     <i class="fas fa-circle-plus"></i>
                     NUEVO
                </button>
            </div>

            <div class="col-7 col-md-6">
                <div class="input-group mb-3 d-flex justify-content-end">
                    <div class="input-group">
                        <input type="text" v-model="query" class="form-control" placeholder="Buscar por dni / nombres / ip / oficina" @keyup.enter="search()">
                        <button class="btn btn-primary" type="button" @click.prevent="search(1)">BUSCAR</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 order-2 order-sm-1" id="tableId">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" ref="tableContainer">
                            <table class="table table-hover table-sm" style="font-size: 12px;">
                                <thead style="vertical-align: middle;">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>OFICINA</th>
                                        <th>DNI</th>
                                        <th>APELLIDOS Y NOMBRE</th>
                                        <th>CARGO</th>
                                        <th>IP</th>
                                        <th>MAC</th>
                                        <th>PUERTO</th>
                                        <th>TIPO EQUIPO</th>
                                        <th>PERTENECE A UGEL</th>
                                        <th>TIPO CONEXIÓN</th>
                                        <th>TIPO DE USO</th>
                                        <th style="min-width: 140px;">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" style="vertical-align: middle;">
                                    <tr v-for="(d, i) in devices" :key="i">
                                        <td>{{ d.id }}</td>
                                        <td>{{ d.office }}</td>
                                        <td>{{ d.dni }}</td>
                                        <td>{{ d.fullname }}</td>
                                        <td>{{ d.charge }}</td>
                                        <td>{{ d.ip }}</td>
                                        <td>{{ d.mac }}</td>
                                        <td>{{ d.port }}</td>
                                        <td>{{ d.type }}</td>
                                        <td>{{ d.is_ugel == 1 ? 'SI' : 'NO' }}</td>
                                        <td>{{ d.connection_type }}</td>
                                        <td>{{ d.use_type }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm me-2" @click.prevent="showModal(1, d, i)">Editar</a>
                                            <a href="#" class="btn btn-danger btn-sm" @click.prevent="destroy(d)">Eliminar</a>
                                        </td>
                                    </tr>
                                    <tr v-if="devices.length === 0">
                                        <td colspan="13">No hay registros</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination
                            :pagination="pagination"
                            :offset="5"
                            @paginate="query === '' ? getData() : search()"
                        ></pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deviceIdModal" tabindex="-1" aria-labelledby="deviceModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" ref="deviceModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="condition ? update() : store()">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deviceModalLabel">{{ condition ? 'Modificar' : 'Agregar' }} Equipos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="office" class="form-label">OFICINA</label>
                                    <input type="text" v-model="device.office" class="form-control uppercase-input" id="office" :class="{ 'is-invalid': errors.office }"/>
                                    <div class="invalid-feedback" v-if="errors.office">
                                        {{ errors.office[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="charge" class="form-label">CARGO</label>
                                    <input type="text" v-model="device.charge" class="form-control uppercase-input" id="charge" :class="{ 'is-invalid': errors.charge }"/>
                                    <div class="invalid-feedback" v-if="errors.charge">
                                        {{ errors.charge[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="dni" class="form-label">DNI</label>
                                    <div class="input-group mb-3">
                                        <input type="text" v-model="device.dni" class="form-control" id="dni" :class="{ 'is-invalid': errors.dni }" aria-describedby="button-addon2" @blur="getPersonByDNI()" @input="clearFullName()"/>
                                        <button class="btn btn-outline-secondary" type="button" id="button-addon2" @click.prevent="getPersonByDNI()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" v-if="errors.dni">
                                        {{ errors.dni[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="fullname" class="form-label">NOMBRE COMPLETO</label>
                                    <input type="text" v-model="device.fullname" class="form-control" id="fullname" :class="{ 'is-invalid': errors.fullname }"/>
                                    <div class="invalid-feedback" v-if="errors.fullname">
                                        {{ errors.fullname[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="ip" class="form-label">IP</label>
                                    <input type="text" v-model="device.ip" class="form-control" id="ip" :class="{ 'is-invalid': errors.ip }"/>
                                    <div class="invalid-feedback" v-if="errors.ip">
                                        {{ errors.ip[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="mac" class="form-label">MAC</label>
                                    <input type="text" v-model="device.mac" class="form-control" id="mac" :class="{ 'is-invalid': errors.mac }" @input="formatMac"/>
                                    <div class="invalid-feedback" v-if="errors.mac">
                                        {{ errors.mac[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="port" class="form-label">PUERTO</label>
                                    <input type="text" v-model="device.port" class="form-control" id="port" :class="{ 'is-invalid': errors.port }"/>
                                    <div class="invalid-feedback" v-if="errors.port">
                                        {{ errors.port[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="type" class="form-label">TIPO</label>
                                    <select v-model="device.type" class="form-select" id="type" :class="{ 'is-invalid': errors.type }">
                                        <option value="PC">PC</option>
                                        <option value="LAPTOP">LAPTOP</option>
                                    </select>
                                    <div class="invalid-feedback" v-if="errors.type">
                                        {{ errors.type[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="is_ugel" class="form-label">PERTENECE A LA UGEL</label>
                                    <select v-model="device.is_ugel" class="form-select" id="is_ugel" :class="{ 'is-invalid': errors.is_ugel }">
                                        <option value="1">SI</option>
                                        <option value="0">NO</option>
                                    </select>
                                    <div class="invalid-feedback" v-if="errors.is_ugel">
                                        {{ errors.is_ugel[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="connection_type" class="form-label">TIPO DE CONEXIÓN</label>
                                    <select v-model="device.connection_type" class="form-select" id="connection_type" :class="{ 'is-invalid': errors.connection_type }">
                                        <option value="CABLE">CABLE</option>
                                        <option value="WIFI">WIFI</option>
                                    </select>
                                    <div class="invalid-feedback" v-if="errors.connection_type">
                                        {{ errors.connection_type[0] }}
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="use_type" class="form-label">TIPO DE USO</label>
                                    <select v-model="device.use_type" class="form-select" id="use_type" :class="{ 'is-invalid': errors.use_type }">
                                        <option value="EXCLUSIVO">EXCLUSIVO</option>
                                        <option value="COMPARTIDO">COMPARTIDO</option>
                                    </select>
                                    <div class="invalid-feedback" v-if="errors.use_type">
                                        {{ errors.use_type[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                {{ condition ? "Modificar" : "Guardar" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { Modal } from "bootstrap";

export default {
    name: "DeviceComponent",
    setup() {
        const devices = ref([]);
        const device = ref({
            id: null,
            office: '',
            dni: '',
            fullname: '',
            ip: '',
            mac: '',
            charge: '',
            port: '',
            type: '',
            is_ugel: '',
            connection_type: '',
            use_type: '',
            indice: null,
        });

        const query = ref('');
        const errors = ref({});
        const condition = ref(null);

        const pagination = ref({
            current_page: null,
            last_page: null,
            path: null,
            from: null,
            to: null,
            total: null,
        });


        const deviceModal = ref(null);
        let thisModal = null;

        onMounted(() => {
            getData();
            thisModal = new Modal(deviceModal.value);
        });

        const notyfSuccess = (text) => {
            notyf.success({
                message: text,
                position: {
                    x: "right",
                    y: "top",
                },
                type: "success",
            });
        }

        const notyfError = (msg = "Sucedió un error...") => {
            notyf.error({
                message: msg,
                position: {
                    x: "right",
                    y: "top",
                },
            });
        };

        const updatePagination = (res) => {
            let data = {
                current_page: res.current_page,
                last_page: res.last_page,
                path: res.path,
                from: res.from,
                to: res.to,
                total: res.total,
            };

            pagination.value = data;
        }

        const formatMac = () => {
            let value = device.value.mac.replace(/[^a-zA-Z0-9]/g, ''); // Eliminar caracteres no alfanuméricos
            if (value.length > 2) {
                value = value.replace(/(.{2})(?=.)/g, '$1:'); // Insertar dos puntos cada dos caracteres
            }
            
            device.value.mac = value.toUpperCase();
        };

        const getData = () => {
            axios.get("/devices/getdata?page=" + pagination.value.current_page)
                .then((response) => {
                    devices.value = response.data.data;
                    updatePagination(response.data);
                }).catch((error) => {
                    console.log(error);
                    notyfError();
                });
        }

        const getPersonByDNI = () => {
            let dni = device.value.dni;
            if(!dni) return;

            axios.get('/person/' + dni + '/getbydni')
                .then((response) => {
                    if(response.data.exists) {
                        let res = response.data.person;
                        device.value.fullname = res.apellidoPaterno + ' ' + res.apellidoMaterno + ', ' + res.nombres;
                    }else notyfError('No se encontró registros');
                }).catch((error) => {
                    notyfError();
                });
        }

        const clearFullName = () => {
            device.value.fullname = null;
        }

        const showModal = (c, data = null, i = null) => {
            clear();
            condition.value = c;

            if(c){
                device.value.id = data.id;
                device.value.office = data.office;
                device.value.dni = data.dni;
                device.value.fullname = data.fullname;
                device.value.ip = data.ip;
                device.value.mac = data.mac;
                device.value.port = data.port;
                device.value.type = data.type;
                device.value.charge = data.charge;
                device.value.is_ugel = data.is_ugel;
                device.value.connection_type = data.connection_type;
                device.value.use_type = data.use_type;
                device.value.indice = i;
            }

            thisModal.show();
        };

        const clear = () => {
            condition.value = null;
            errors.value = {};
            // device.value.office = null;
            device.value.dni = null;
            device.value.fullname = null;
            device.value.ip = null;
            device.value.mac = null;
            device.value.port = null;
            device.value.charge = null;
            device.value.type = 'PC';
            device.value.is_ugel = '1';
            device.value.connection_type = 'CABLE';
            device.value.use_type = 'EXCLUSIVO';
            device.value.indice = null;
        };

        const store = () => {
            let data = device.value;
            errors.value = {};
            axios.post("/devices", data)
                .then((response) => {
                    pagination.value.current_page = 1;
                    getData();
                    clear();
                    notyfSuccess(response.data.message);
                    // thisModal.hide();
                })
                .catch((error) => {
                    if (error.response.status === 422) errors.value = error.response.data.errors;
                    else notyfError();
                });
        }

        const search = (i = 0) => {
            if(i) pagination.value.current_page = 1;
            errors.value = {};
            axios.post("/devices/search?page=" + pagination.value.current_page, {search: query.value})
                .then((response) => {
                    devices.value = response.data.data;
                    updatePagination(response.data);
                })
                .catch((error) => {
                    if (error.response.status === 422) errors.value = error.response.data.errors;
                    else notyfError();
                });
        }

        const update = () => {
            let data = device.value;
            errors.value = {};
            axios.put("/devices/" + data.id, data)
                .then((response) => {
                    pagination.value.current_page = 1;
                    getData();
                    clear();
                    notyfSuccess(response.data.message);
                    thisModal.hide();
                })
                .catch((error) => {
                    if (error.response.status === 422) errors.value = error.response.data.errors;
                    else notyfError();
                });
        }

        const destroy = (data) => {
            const confirmation = confirm('¿Estás seguro de que deseas eliminar este registro?');

            if (confirmation) {
                axios.delete("/devices/" + data.id)
                .then((response) => {
                    pagination.value.current_page = 1;
                    getData();
                    clear();
                    notyfSuccess(response.data.message);
                })
                .catch((error) => {
                    if (error.response.status === 422) errors.value = error.response.data.errors;
                    else notyfError();
                });
            }
        }

        return {
            devices,
            device,
            condition,
            pagination,
            query,
            deviceModal,
            errors,
            formatMac,
            showModal,
            getPersonByDNI,
            clearFullName,
            getData,
            store,
            update,
            search,
            destroy,
        };
    },
};
</script>
<style scoped>
    .uppercase-input {
        text-transform: uppercase; /* Convierte el texto a mayúsculas */
    }
</style>