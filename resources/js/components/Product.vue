<template>
    <div class="mx-5 my-5">
        <Loader v-if="isloaded"/>
        <div class="container-fluid" v-show="!isloaded">
            <div class="row">
                <div class="col-4 mt-3">
                    <h3>{{ isEditMode ? `Edit` : `Create` }}</h3>
                    <div class="card p-3">
                        <!--Create & Edit Form-->
                        <form
                            @submit.prevent="
                                isEditMode ? productUpdate() : productStore()
                            "
                            @keydown="product.onKeydown($event)"
                        >
                            <AlertError :form="product" :message="ermessage" />
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    autocomplete="off"
                                    v-model="product.name"
                                />
                                <div v-if="product.errors.has('name')">
                                    <HasError :form="product" field="name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input
                                    type="text"
                                    name="price"
                                    class="form-control"
                                    autocomplete="off"
                                    v-model="product.price"
                                />
                                <div v-if="product.errors.has('price')">
                                    <HasError :form="product" field="price" />
                                </div>
                            </div>
                            <Button :form="product" class="btn btn-primary"
                                >Save</Button
                            >
                        </form>
                        <!--End Create & Edit Form-->
                    </div>
                </div>
                <div class="col-8">
                    <div class="d-flex justify-content-between mb-4">
                        <button class="btn btn-primary" @click="productCreate">
                            Create
                        </button>
                        <form @submit.prevent="productGet">
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="search"
                                    class="form-control"
                                    name="search"
                                    v-model="search"
                                />
                                <div class="input-group-append">
                                    <button class="btn btn-primary text-white">
                                        search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table class="table border">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tr v-for="product in products.data" :key="product.id">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>
                                {{
                                    new Intl.NumberFormat("en-IN").format(
                                        product.price
                                    )
                                }}
                            </td>
                            <td>
                                <button
                                    class="btn btn-warning btn-sm"
                                    @click="productEdit(product)"
                                >
                                    Edit
                                </button>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="productDestroy(product.id)"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </table>

                    <pagination
                        :data="products"
                        @pagination-change-page="productGet"
                    ></pagination>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Button, HasError, AlertError } from "vform/src/components/bootstrap4"
import Loader from "./Loader"

export default {
    name: "Product",
    components: {
        Button,
        HasError,
        AlertError,
        Loader,
    },
    data() {
        return {
            isloaded : true,
            isEditMode: false,
            search: "",
            products: {},
            ermessage: "",
            product: new Form({
                id: "",
                name: "",
                price: ""
            })
        };
    },
    methods: {
        productGet(page = 1) {
            this.$Progress.start();
            axios
                .get(`/api/products?page=${page}&search=${this.search}`)
                .then(response => {
                    this.products = response.data;
                    this.$Progress.finish();
                    setTimeout(() => {
                        this.isloaded = false
                    }, 500)
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        async productStore() {
            // axios.post(`/api/products`, this.product)
            // .then((response) => {
            //     this.productGet()
            //     this.product = {id: '', name: '', price: ''}
            // })
            // .catch(() => console.log("Error"))

            try {
                const response = await this.product.post(`/api/products`);
                this.productGet();
                // this.product = {id: '', name: '', price: ''}
                this.product.reset();
                this.alertMessage("Created");
            } catch (error) {
                this.ermessage = error.response.data.message;
            }
        },
        productCreate() {
            this.product.clear();
            this.isEditMode = false;
            // this.product = {id: '', name: '', price: ''}
            this.product.reset();
        },
        productEdit(product) {
            this.product.clear();
            this.isEditMode = true;
            // this.product.id = product.id
            // this.product.name = product.name
            // this.product.price = product.price
            this.product.fill(product);
        },
        async productUpdate() {
            //   axios.put(`/api/products/${this.product.id}`, this.product)
            //   .then((response) => {
            //     this.productGet()
            //     this.isEditMode = false
            //     this.product = {id: '', name: '', price: ''}
            //   })
            //   .catch(() => console.log("Error"))

            try {
                const response = await this.product.put(
                    `/api/products/${this.product.id}`
                );
                this.productGet();
                this.isEditMode = false;
                // this.product = {id: '', name: '', price: ''}
                this.product.reset();
                this.alertMessage("Updated");
            } catch (error) {
                this.ermessage = error.response.data.message;
            }
        },
        productDestroy(id) {
            Swal.fire({
                title: "Are you sure?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#675cd8",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete this product"
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/products/${id}`).then(res => {
                        this.productGet();
                        this.alertMessage("Deleted");
                    });
                }
            });
        },
        alertMessage(action) {
            Toast.fire({
                icon: "success",
                title: `${action} Successfully!`
            });
        }
    },
    created() {
        this.productGet();
    }
};
</script>
