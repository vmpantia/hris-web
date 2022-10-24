<template>
    <div class="p-3">
        
        <div class="cstm-page-title">
            <i class="fas fa-fw fa-building"></i>
            <span>Departments</span>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <button class="btn btn-sm btn-primary" v-on:click="addNew"><i class="fa fa-plus mr-2" aria-hidden="true"></i> New Barangay</button>
                    <div class="card-tools">
                        <div class="input-group input-group-md" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search" v-model="searchValue">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" v-on:click="search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Barangay</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="dataTables.length === 0">
                        <tr>
                            <tr>
                                <td colspan="5" class="text-center">
                                    No Record Found
                                </td>
                            </tr>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <template v-for="data in dataTables">
                            <tr>
                                <td>{{ data.city_name }}</td>
                                <td>{{ data.name }}</td>
                                <td>{{ formatDate(data.created_at) }}</td>
                                <td>{{ formatDate(data.updated_at) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" v-on:click="edit(data.id)"><i class="fas fa-edit mr-2" aria-hidden="true"></i>Edit</button> &nbsp;
                                    <button class="btn btn-sm btn-danger" v-on:click="destroy(data.id)"><i class="fa fa-trash mr-2" aria-hidden="true"></i> Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="modal" id="new-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-if="isNew">
                                <i class="fa fa-building"></i> &nbsp;
                                New Barangay</h5>
                            <h5 class="modal-title" v-else>
                                <i class="fa fa-building"></i> &nbsp;
                                Edit Barangay</h5>

                            <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert" style="background: #fff3cd; border: #ffecb5; color: #664d03">
                                Field with <b>Asterisk (*)</b> are Required.
                            </div>
                            <!-- Province Selection Field -->
                            <div class="form-group mb-3">
                                <label class="form-label">* Select Barangay:</label>
                                <select type="text" class="form-control" v-model="dataValue.city_id" >   
                                    <option value="">Select Barangay</option>
                                    <option v-for="data in cities_dataTables" :value="data.id">{{ data.name }}</option>
                                </select>
                                <span class="text-danger" v-if="error && error.city_id">{{ error.city_id[0] }}</span>
                            </div>
                            <!-- City Name Field -->
                            <div class="form-group mb-3">
                                <label class="form-label">* Barangay Name:</label>
                                <input type="text" class="form-control" v-model="dataValue.name" placeholder="Barangay">
                                <span class="text-danger" v-if="error && error.name">{{ error.name[0] }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="store" v-if="isNew">
                                <i class="fa fa-cloud"></i>&nbsp;
                                Save Changes</button>
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="update" v-else>
                                <i class="fa fa-cloud"></i>&nbsp;
                                Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>   

    </div>
</template>


<script>

export default {
    //Declaration of Variables
    data() {
        return {
            dataTables: [],
            cities_dataTables:[],
            dataValue: [],
            error: [],
            isNew: false,
            searchValue: '',
        }
    },
    methods: {

        //Function for Displaying Message Box
        messageBox(title, text, type) {
            //Display Message Box
            this.$fire({
                title: title,
                text: text,
                type: type,
            })
            
            //Call function getData() 
            this.getData();
        },

        //Function for formatting date values to this format MM-DD-YYYY hh:mm
        formatDate(date) {
            //Format Dates
            return moment(date).format('MM-DD-YYYY hh:mm');
        },

        //Function for getting latest data of barangays in the database
        getData() {
            //Get Request from url 'barangay/getData'
            axios.get('barangay/getData')
                .then(response => {
                    //Store data from dataTables variable
                    this.dataTables = response.data.data;
                });
        },

        //Function for getting latest data of cities in the database
        getCitiesData() {
            //Get Request from url 'city/getData'
            axios.get('city/getData')
                .then(response => {
                    //Store data from cities_dataTables variable
                    this.cities_dataTables = response.data.data;
                });
        },

        //Function for searching specific barangay in the database
        search() {
            //Check if searchValue has value
            if(this.searchValue === '') {
                //If searchValue is empty call function getData()
                this.getData();
                return false;
            }
            
            //If searchValue is NOT empty get request from url 'barangay/search'
            axios.get('barangay/search/' + this.searchValue)
                .then(response => {
                    //Store data from dataTables variable
                    this.dataTables = response.data.data;
                });
        },

        //Function for creating new Barangay
        addNew() {
            //Reset Variables
            this.dataValue = [];
            this.error = [];
            this.isNew = true;
            this.dataValue.city_id = '';

            //Open Modal
            $('#new-modal').modal('show');
        },

        //Function for edit Barangay
        edit(id) {
            //Reset Variables
            this.dataValue = [];
            this.error = [];
            this.isNew = false;

            //Find id in dataTables
            Object.keys(this.dataTables).forEach(key => {
                //Check if id is match in the current data in dataTables
                if(this.dataTables[key].id === id) {
                    //Store data in dataValue
                    this.dataValue.name = this.dataTables[key].name;
                    this.dataValue.id = this.dataTables[key].id;
                    this.dataValue.city_id = this.dataTables[key].city_id;
                    return false;
                }
            });

            //Open Modal
            $('#new-modal').modal('show');
        },

        //Function for saving Barangay Information
        store() {
            //Generate Post Request to barangay/store
            axios.post('barangay/store', {
                name : this.dataValue.name,
                city_id : this.dataValue.city_id
            })
            .then(response => {
                if(response.status === 200) {
                    this.messageBox('Success', 'Data Successfully Saved', 'success');
                    $('#new-modal').modal('hide');
                }
            })
            .catch(errors => {
                if(errors.response.status === 422) {
                    this.error = errors.response.data.errors;
                }
                else {
                    this.messageBox('Failed', errors.response.data.err_msg , 'error');
                }
            });
        },
        
        //Function for updating Barangay Information
        update() {
            //Generate Post Request to barangay/store
            axios.post('barangay/store', {
                id: this.dataValue.id,
                name : this.dataValue.name,
                city_id : this.dataValue.city_id
            })
            .then(response => {
                if(response.status === 200) {
                    this.messageBox('Success', 'Data Successfully Updated', 'success');
                    $('#new-modal').modal('hide');
                }
            })
            .catch(errors => {
                if(errors.response.status === 422) {
                    this.error = errors.response.data.errors;
                }
                else {
                    this.messageBox('Failed', errors.response.data.err_msg , 'error');
                }
            });
        },

        //Function for deleting Barangay
        destroy(id) {
            this.$confirm('Are you sure you want to delete this data?', 'Delete?', 'question')
            .then(res => {
                axios.get('barangay/destroy/' + id)
                .then(response => { 
                    if(response.status === 200) {
                        this.messageBox('Success', 'Data Successfully Deleted', 'success');
                    }
                })  
            });
        }
    },
    mounted() {
        this.getData();
        this.getCitiesData();
    },
}
</script>