<template>
    <div class="p-3">
        
        <div class="cstm-page-title">
            <i class="fas fa-fw fa-building"></i>
            <span>Departments</span>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <button class="btn btn-sm btn-primary" v-on:click="newDepartment"><i class="fa fa-plus mr-2" aria-hidden="true"></i> New Barangay</button>
                    <div class="card-tools">
                        <div class="input-group input-group-md" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search" v-model="keyword">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default" v-on:click="searchDepartments">
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
                            <th>Department</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="dep_list.length === 0">
                        <tr>
                            <tr>
                                <td colspan="5" class="text-center">
                                    No Record Found
                                </td>
                            </tr>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <template v-for="dep in dep_list">
                            <tr>
                                <td>{{ dep.dep_name }}</td>
                                <td>{{ dep.dep_manager_id }}</td>
                                <td>{{ dep.dep_status }}</td>
                                <td>{{ formatDate(dep.created_at) }}</td>
                                <td>{{ formatDate(dep.updated_at) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" v-on:click="editDepartment(dep)"><i class="fas fa-edit mr-2" aria-hidden="true"></i>Edit</button>
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
                                New Department</h5>
                            <h5 class="modal-title" v-else>
                                <i class="fa fa-building"></i> &nbsp;
                                Edit Department</h5>

                            <button type="button" class="close"  data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert" style="background: #fff3cd; border: #ffecb5; color: #664d03">
                                Field with <b>Asterisk (*)</b> are Required.
                            </div>
                            <!-- City Name Field -->
                            <div class="form-group mb-3">
                                <label class="form-label">* Department Name:</label>
                                <input type="text" class="form-control" v-model="dep_info.dep_name" placeholder="Input Department Name">
                                <span class="text-danger" v-if="error_messages && error_messages.dep_name">{{ error_messages.dep_name[0] }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="saveDepartment" v-if="isNew">
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
            dep_list: [],
            manager_list:[],
            dep_info: [],
            error_messages: [],
            isNew: false,
            keyword: "",
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
            this.getDepartments();
        },

        //Function for formatting date values to this format MM-DD-YYYY hh:mm
        formatDate(date) {
            //Format Dates
            return moment(date).format('MM-DD-YYYY hh:mm');
        },

        //Function for getting all data from database
        getDepartments() {
            axios.get('department/getDepartments')
                .then(response => {
                    this.dep_list = response.data.data;
                });
        },
        
        //Function for creating new data
        newDepartment() {
            //Reset Variables
            this.dep_info = [];
            this.error_messages = [];
            this.isNew = true;
            this.dep_info.dep_manager_id = '';

            //Open Modal
            $('#new-modal').modal('show');
        },

        //Function for searching specific data in database
        searchDepartments() {
            //Check if keyword has value
            if(this.keyword === '') {
                //If keyword is empty get all
                this.getDepartments();
                return false;
            }
            
            //If keyword is NOT empty get request from url 'barangay/search'
            axios.get('department/searchDepartments/' + this.keyword)
                .then(response => {
                    this.dep_list = response.data.data;
                });
        },

        //Function for edit data
        editDepartment(dep) {
            //Reset Variables
            this.dep_info = [];
            this.error_messages = [];
            this.isNew = false;

            //Store data in variable
            this.dep_info.id = dep.id;
            this.dep_info.dep_name = dep.dep_name;
            this.dep_info.dep_manager_id = dep.dep_manager_id;
            this.dep_info.dep_status = dep.dep_status;

            //Open Modal
            $('#new-modal').modal('show');
        },

        //Function for saving data
        saveDepartment() {
            //Post save data
            axios.post('department/saveDepartment', {
                id: this.dep_info.id,
                dep_name: this.dep_info.dep_name,
                dep_manager_id: this.dep_info.dep_manager_id,
                dep_status: this.dep_info.dep_status
            })
            .then(response => {
                if(response.status === 200) {
                    this.messageBox('Success', 'Data Successfully Saved', 'success');
                    $('#new-modal').modal('hide');
                }
            })
            .catch(errors => {
                if(errors.response.status === 422) {
                    this.error_messages = errors.response.data.errors;
                }
                else {
                    this.messageBox('Failed', errors.response.data.err_msg , 'error');
                }
            });
        },
    },
    mounted() {
        
        this.getDepartments();
    },
}
</script>