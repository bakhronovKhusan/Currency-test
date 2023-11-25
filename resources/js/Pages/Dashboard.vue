<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const currencyData = ref(null);
const valueID = ref('R01010');
const fromDate = ref(new Date().toISOString().substr(0, 10));
const toDate = ref(new Date().toISOString().substr(0, 10));
const pageCount = ref(null);

onMounted(() => {
    fetchCurrencyData();
});

function fetchCurrencyData(page = 1) {
    // Подготовка параметров запроса
    const queryParams = new URLSearchParams({
        valuteID: valueID.value,
        from: fromDate.value,
        to: toDate.value,
        page: page

    }).toString();

    const url = `/api/currency/?${queryParams}`;
    fetch(url, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ` + document.querySelector('input#token').getAttribute('value'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            currencyData.value = data.data;
            pageCount.value = data.last_page;
        })
        .catch(error => console.error('Error:', error));
}


</script>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
.pagination {
    display: inline-block;
    margin: 10px auto;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    margin: 0 4px;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}
.pagination a:hover:not(.active) {background-color: #ddd;}
</style>



<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            <input type="hidden" id="token" :value="$page.props.token">
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                    <div style="padding: 10px;width: 100%;" id="currency">
                            <input v-model="valueID" placeholder="Value ID" @change="fetchCurrencyData()">
                            <input v-model="fromDate" type="date" placeholder="From Date" @change="fetchCurrencyData()">
                            <input v-model="toDate" type="date" placeholder="To Date" @change="fetchCurrencyData()">
                            <div v-if="currencyData" style="margin-top: 10px">
                                <!-- Отображение результатов -->
                                <table>
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Valute ID</th>
                                        <th>Num code</th>
                                        <th>Char code</th>
                                        <th>Valute name</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tr v-for="(rate, index) in currencyData" :key="index">
                                        <td>{{ rate.id }}</td>
                                        <td>{{ rate.valuteID }}</td>
                                        <td>{{ rate.numCode }}</td>
                                        <td>{{ rate.charCode }}</td>
                                        <td>{{ rate.name }}</td>
                                        <td>{{ rate.date }}</td>
                                    </tr>
                                </table>
                                <div style="text-align: center;">
                                <div class="pagination">
                                        <a class="page-item" v-for="page in pageCount" :key="page" @click="fetchCurrencyData(page)" style="cursor: pointer">
                                            {{ page }}
                                        </a>
                                </div>
                                </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
