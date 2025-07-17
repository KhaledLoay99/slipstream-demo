<template>
    <div>
        <div class="mb-4 flex items-center">
            <input v-model="search" placeholder="Search..." class="border border-gray-300 p-2 rounded flex-grow mr-4 focus:outline-none focus:border-blue-500">
            <select v-model="categoryFilter" class="border border-gray-300 p-2 rounded mr-4 focus:outline-none focus:border-blue-500">
                <option value="">Select Category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>
            <button @click="createCustomer" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Create</button>
        </div>

        <div v-if="loading" class="text-center text-gray-500">Loading...</div>
        <table v-else class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Customer Name</th>
                    <th class="border p-2 text-left">Reference</th>
                    <th class="border p-2 text-left">Category</th>
                    <th class="border p-2 text-left">No of Contacts</th>
                    <th class="border p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="customer in filteredCustomers" :key="customer.id" class="hover:bg-gray-50">
                    <td class="border p-2">{{ customer.name }}</td>
                    <td class="border p-2">{{ customer.reference }}</td>
                    <td class="border p-2">{{ customer.category.name }}</td>
                    <td class="border p-2">{{ customer.contacts.length }}</td>
                    <td class="border p-2">
                        <button @click="editCustomer(customer)" class="text-blue-500 mr-2 hover:underline">Edit</button>
                        <button @click="deleteCustomer(customer)" class="text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
                <tr v-if="filteredCustomers.length === 0">
                    <td colspan="5" class="text-center">No customers found</td>
                </tr>
            </tbody>
        </table>

        <transition name="fade">
            <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-xl w-3/4 max-w-2xl">
                    <h2 class="text-lg font-bold mb-4">{{ editing ? 'Customer - Detail' : 'Customer - Detail' }}</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700">Name</label>
                            <input v-model="form.name" class="border p-2 w-full rounded" :class="{ 'border-red-500': errors.name }">
                            <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</p>

                            <label class="block text-gray-700 mt-2">Reference</label>
                            <input v-model="form.reference" class="border p-2 w-full rounded" :class="{ 'border-red-500': errors.reference }">
                            <p v-if="errors.reference" class="text-red-500 text-sm">{{ errors.reference }}</p>

                            <label class="block text-gray-700 mt-2">Category</label>
                            <select v-model="form.customer_category_id" class="border p-2 w-full rounded" :class="{ 'border-red-500': errors.customer_category_id }">
                                <option value="">Select...</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            <p v-if="errors.customer_category_id" class="text-red-500 text-sm">{{ errors.customer_category_id }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700">Start Date</label>
                            <input v-model="form.start_date" type="date" class="border p-2 w-full rounded" :class="{ 'border-red-500': errors.start_date }">
                            <p v-if="errors.start_date" class="text-red-500 text-sm">{{ errors.start_date }}</p>

                            <label class="block text-gray-700 mt-2">Description</label>
                            <textarea v-model="form.description" class="border p-2 w-full rounded h-24" :class="{ 'border-red-500': errors.description }"></textarea>
                            <p v-if="errors.description" class="text-red-500 text-sm">{{ errors.description }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-md font-bold mb-2">Contacts</h3>
                        <table class="w-full border-collapse border border-gray-300 mb-4">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-2 text-left">First Name</th>
                                    <th class="border p-2 text-left">Last Name</th>
                                    <th class="border p-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contact in form.contacts" :key="contact.id" class="hover:bg-gray-50">
                                    <td class="border p-2">{{ contact.first_name }}</td>
                                    <td class="border p-2">{{ contact.last_name }}</td>
                                    <td class="border p-2">
                                        <button @click="editContact(contact)" class="text-blue-500 mr-2 hover:underline">Edit</button>
                                        <button @click="deleteContact(contact)" class="text-red-500 hover:underline">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button @click="createContact" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Create</button>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="saveCustomer" class="bg-blue-500 text-white p-2 rounded mr-2 hover:bg-blue-600" :disabled="loading">Save</button>
                        <button @click="closeModal" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600">Back</button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Contact Modal with Transition -->
        <transition name="fade">
            <div v-if="showContactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-xl w-1/2">
                    <h2 class="text-lg font-bold mb-4">{{ editingContact ? 'Contacts - Detail' : 'Contacts - Detail' }}</h2>
                    <label class="block text-gray-700">First Name</label>
                    <input v-model="contactForm.first_name" class="border p-2 w-full rounded" :class="{ 'border-red-500': contactErrors.first_name }">
                    <p v-if="contactErrors.first_name" class="text-red-500 text-sm">{{ contactErrors.first_name }}</p>

                    <label class="block text-gray-700 mt-2">Last Name</label>
                    <input v-model="contactForm.last_name" class="border p-2 w-full rounded" :class="{ 'border-red-500': contactErrors.last_name }">
                    <p v-if="contactErrors.last_name" class="text-red-500 text-sm">{{ contactErrors.last_name }}</p>

                    <div class="mt-6 flex justify-end">
                        <button @click="saveContact" class="bg-blue-500 text-white p-2 rounded mr-2 hover:bg-blue-600" :disabled="loading">Save</button>
                        <button @click="closeContactModal" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600">Back</button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['initialCustomers', 'categories'],
    data() {
        return {
            search: '',
            categoryFilter: '',
            showModal: false,
            showContactModal: false,
            editing: false,
            editingContact: false,
            loading: false,
            form: { id: null, name: '', reference: '', customer_category_id: '', start_date: '', description: '', contacts: [] },
            contactForm: { id: null, first_name: '', last_name: '', customer_id: '' },
            customers: [],
            errors: {},
            contactErrors: {},
        };
    },
    mounted() {
        this.customers = this.initialCustomers;
    },
    computed: {
        filteredCustomers() {
            return this.customers.filter(customer => {
                const matchesSearch = customer.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                     customer.reference.toLowerCase().includes(this.search.toLowerCase());
                const matchesCategory = !this.categoryFilter || customer.customer_category_id == this.categoryFilter;
                return matchesSearch && matchesCategory;
            });
        },
    },
    methods: {
        validateCustomer() {
            this.errors = {};
            if (!this.form.name) this.errors.name = 'Name is required';
            if (!this.form.reference) this.errors.reference = 'Reference is required';
            if (!this.form.customer_category_id) this.errors.customer_category_id = 'Category is required';
            if (!this.form.start_date) this.errors.start_date = 'Start Date is required';
            if (!this.form.description) this.errors.description = 'Description is required';
            return Object.keys(this.errors).length === 0;
        },
        validateContact() {
            this.contactErrors = {};
            if (!this.contactForm.first_name) this.contactErrors.first_name = 'First Name is required';
            if (!this.contactForm.last_name) this.contactErrors.last_name = 'Last Name is required';
            return Object.keys(this.contactErrors).length === 0;
        },
        createCustomer() {
            this.form = { id: null, name: '', reference: '', customer_category_id: '', start_date: '', description: '', contacts: [] };
            this.editing = false;
            this.showModal = true;
        },
        editCustomer(customer) {
            this.form = { ...customer, contacts: [...customer.contacts] };
            this.editing = true;
            this.showModal = true;
        },
        saveCustomer() {
            if (!this.validateCustomer()) return;
            this.loading = true;
            const url = this.editing ? `/customers/${this.form.id}` : '/customers';
            const method = this.editing ? 'put' : 'post';
            axios[method](url, this.form).then(() => {
                this.loading = false;
                this.closeModal();
                this.fetchCustomers();
            }).catch(error => {
                this.loading = false;
                console.error(error);
            });
        },
        deleteCustomer(customer) {
            if (confirm('Are you sure you want to delete this customer?')) {
                this.loading = true;
                axios.delete(`/customers/${customer.id}`).then(() => {
                    this.loading = false;
                    this.fetchCustomers();
                }).catch(error => {
                    this.loading = false;
                    console.error(error);
                });
            }
        },
        closeModal() {
            this.showModal = false;
            this.errors = {};
        },
        createContact() {
            this.contactForm = { id: null, first_name: '', last_name: '', customer_id: this.form.id };
            this.editingContact = false;
            this.showContactModal = true;
        },
        editContact(contact) {
            this.contactForm = { ...contact };
            this.editingContact = true;
            this.showContactModal = true;
        },
        saveContact() {
            if (!this.validateContact()) return;

            const exists = this.form.contacts.some(contact => {
                if (this.editingContact && contact.id === this.contactForm.id) return false; // Skip self for edits
                return contact.first_name === this.contactForm.first_name && contact.last_name === this.contactForm.last_name;
            });

            if (exists) {
                alert('Contact already existing');
                return;
            }

            this.loading = true;
            const url = this.editingContact ? `/contacts/${this.contactForm.id}` : '/contacts';
            const method = this.editingContact ? 'put' : 'post';
            axios[method](url, this.contactForm).then(response => {
                this.loading = false;
                this.closeContactModal();
                if (this.editingContact) {
                    const index = this.form.contacts.findIndex(c => c.id === this.contactForm.id);
                    if (index !== -1) this.form.contacts.splice(index, 1, response.data);
                } else {
                    this.form.contacts.push(response.data);
                }
                this.fetchCustomers();
            }).catch(error => {
                this.loading = false;
                console.error(error);
            });
        },
        deleteContact(contact) {
            if (confirm('Are you sure you want to delete this contact?')) {
                this.loading = true;
                axios.delete(`/contacts/${contact.id}`).then(() => {
                    this.loading = false;
                    const index = this.form.contacts.findIndex(c => c.id === contact.id);
                    if (index !== -1) this.form.contacts.splice(index, 1);
                    this.fetchCustomers();
                }).catch(error => {
                    this.loading = false;
                    console.error(error);
                });
            }
        },
        closeContactModal() {
            this.showContactModal = false;
            this.contactErrors = {};
        },
        fetchCustomers() {
            this.loading = true;
            axios.get('/customers').then(response => {
                this.customers = response.data.customers || response.data;
                this.loading = false;
            }).catch(error => {
                this.loading = false;
                console.error(error);
            });
        },
    },
};
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>