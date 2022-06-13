<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create Employer</div>

                    <div class="card-body">
                        <form @submit.prevent="submitForm()">
                            <div class="form-group">
                                <label for="NameInput">Name</label>
                                <input type="text" class="form-control" id="NameInput" v-model="name" aria-describedby="NameHelp">
                                <small id="NameHelp" class="danger-text form-text text-danger" v-text="validationErrors.name"></small>
                            </div>
                            <div class="form-group">
                                <label for="NameInput">National ID</label>
                                <input type="text" class="form-control" id="NationalIDInput" v-model="national_id" aria-describedby="NameHelp">
                                <small id="NationalIDHelp" class="danger-text form-text text-danger" v-text="validationErrors.national_id"></small>
                            </div>
                            <div class="form-group">
                                <label for="video_file">Video Introduction</label>
                                <input accept="video/mp4" type="file" class="form-control" ref="videoFile" id="video_file" v-on:change="handleVideoUpload()">
                                <small class="danger-text form-text text-danger" v-text="validationErrors.video_file"></small>
                            </div>
                            <div class="form-group">
                                <label for="imageFile">Personal Picture</label>
                                <input accept="image/png, image/jpg, image/jpeg" type="file" class="form-control" ref="imageFile" id="imageFile" v-on:change="handleImageUpload()">
                                <small class="danger-text form-text text-danger" v-text="validationErrors.image_file"></small>
                            </div>
                            <hr>
                            <progress class="w-100" max="100" :value.prop="uploadPercentage"></progress>

                            <div class="form-group text-center">
                                <button type="submit" :disabled="!activeSubmit" class="btn btn-primary w-50 ">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2'

    export default {
        data() {
            return {
                name: '',
                national_id: '',
                videoFile: '',
                ImageFile: '',
                validationErrors:{},
                uploadPercentage: 0,
                activeSubmit:true,
                nameRegex:/[\u0620-\u064A\040a-zA-Z ]+$/,
                nameRegexEN:/^[a-zA-Z ]*$/,
                NationalRegex:/^\d+$/,
            }
        },

        computed:{

        },
        methods: {
            handleVideoUpload(){
                //file size in MB
                let fileSize=Math.round(((this.$refs.videoFile.files[0].size)/1024)/1024);
                if(fileSize>20){
                    Vue.set(this.validationErrors,'video_file','Video file must be less than 20 MB')
                    //this.validationErrors.video_file='Video file must be less than 20 MB';
                    this.videoFile = '';
                }else{
                    this.videoFile = this.$refs.videoFile.files[0];
                    Vue.delete(this.validationErrors,'video_file')
                }
            },
            handleImageUpload(){
                //file size in MB
                let fileSize=Math.round(((this.$refs.imageFile.files[0].size)/1024)/1024);
                if(fileSize>10){
                    Vue.set(this.validationErrors,'image_file','image file must be less than 10 MB')
                    this.ImageFile = '';
                }else{
                    this.ImageFile = this.$refs.imageFile.files[0];
                    Vue.delete(this.validationErrors,'image_file')
                }
            },
            validateForm(){
                /*
                validate Name
                */
                if(this.name.trim().length<3)
                    Vue.set(this.validationErrors,'name','Employer name min length 3 character')
                //this.validationErrors.name='Employer name min length 3 character';
                else if(this.name.trim().length>50)
                    Vue.set(this.validationErrors,'name','Employer name max length 50 character')
                //this.validationErrors.name='Employer name max length 50 character';
                else if(!this.name.match(this.nameRegex))
                    Vue.set(this.validationErrors,'name','Employer name must contain only characters')
                //this.validationErrors.name='Employer name must contain only characters';
                else
                    Vue.delete(this.validationErrors,'name')
                /*
                validate National ID
                */
                if(this.national_id.trim().length!=11)
                    Vue.set(this.validationErrors,'national_id','National ID must be 11 number')
                //this.validationErrors.national_id='National ID must be 11 number';
                else if(!this.national_id.match(this.NationalRegex))
                    Vue.set(this.validationErrors,'national_id','National ID must contain only numbers')
                //this.validationErrors.national_id='National ID must contain only numbers';
                else
                    Vue.delete(this.validationErrors,'national_id')

                return Object.keys(this.validationErrors).length<1;
            },
            submitForm() {

                this.uploadPercentage=0;
                this.activeSubmit=false;
                if(this.validateForm()===false) {
                    this.fireErrorValidation();
                    this.activeSubmit=true;
                    return 0;
                }
                /*
                Initialize the form data
                */
                let formData = new FormData();

                /*
                  Add the form data we need to submit
                */
                formData.append('name', this.name);
                formData.append('national_id', this.national_id);
                formData.append('video_file', this.videoFile);
                formData.append('image_file', this.ImageFile);

                /*
                  Make the request to the Upload URL
                */
                axios.post( '/employee', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function( progressEvent ) {
                        this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ) );
                    }.bind(this)
                }).then(()=>{
                    this.fireSuccessSubmit();
                }).catch( (error)=> {
                    this.activeSubmit=true;
                    if (error.response) {
                        if(error.response.status==422) {
                            this.validationErrors=error.response.data.errors;
                            this.fireErrorValidation();
                        }else{
                            console.log(error.response.data);
                        }
                    }
                })
            },
            fireErrorValidation() {
                this.uploadPercentage=0;
                Swal.fire({
                    title:'Error Validation',
                    text:'please solve validation errors',
                    timer:1100,
                    icon:"error"
                })
            },
            fireSuccessSubmit() {
                this.name='';
                this.national_id='';
                this.videoFile='';
                this.ImageFile='';
                this.validationErrors= {};
                this.$refs.videoFile.value=null;
                this.$refs.imageFile.value=null;
                this.activeSubmit=true;
                this.uploadPercentage=0;
                Swal.fire({
                    title:'Inserted Success',
                    text:'New Employer Created',
                    timer:1100,
                    icon:"success"
                })
            },
        }
    }
</script>
