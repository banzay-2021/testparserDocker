<template>
    <div>
        <div class="form-group">
            <!--router-link :to="{name: 'mewParsing'}" class="btn btn-success">New parsing</router-link-->
        </div>

        <div class="panel panel-default">
            <div class="h1">Sites list</div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Points</th>
                        <th>Item ID</th>
                        <th>Created</th>
                        <th width="150">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item, index in items" class="align-baseline">
                        <td>{{ item.id }}</td>
                        <td>
                            <a v-bind:href="item.link">
                                {{ item.title }}
                            </a>

                        </td>
                        <td>{{ item.points }}</td>
                        <td>{{ item.id_item }}</td>
                        <td>{{ item.created }}</td>
                        <td>
                            <a href="#"
                               class="btn btn-xs btn-outline-primary"
                               v-on:click="updatePoints(item.id_item, index)">
                                Update points
                            </a>
                        </td>
                        <!--td>
                            <router-link :to="{name: 'editCompany', params: {id: item.id}}" class="btn btn-xs btn-default">
                                Edit
                            </router-link>
                            <a href="#"
                               class="btn btn-xs btn-danger"
                               v-on:click="deleteEntry(item.id, index)">
                                Delete
                            </a>
                        </td-->
                    </tr>
                    </tbody>
                </table>
                <ul class="pagination justify-content-center">
                    <li v-for="link in links">
                        <div class="page-item" v-bind:class="{'disabled' : !link.url, 'active' : link.active}">
                            <a v-bind:href="link.url" class="page-link">{{ link.label }}</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ParserIndex",
    data: function () {
        return {
            items: [],
            lastPage: null,
            lastPageUrl: null,
            links: {},
            nextPageUrl: null,
            perPage: null,
            prevPageUrl: null,
            page: null,
            itemsPage: null,
        }
    },
    mounted() {
        var app = this;
        axios.post('/api/v1/sites')
            .then(function (resp) {
                app.items = resp.data.data;
                app.lastPage = resp.data.last_page;
                app.lastPageUrl = resp.data.last_page_url;
                app.links = resp.data.links;
                app.nextPageUrl = resp.data.next_page_url;
                app.perPage = resp.data.per_page;
                app.prevPageUrl = resp.data.prev_page_url;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Could not load companies");
            });
    },
    methods: {
        getResults(page) {
            var app = this;
            if (typeof page === "undefined") {
                page = 1;
            }
            axios.post("/api/v1/sites?page=" + page)
                .then(response => {
                    app.links = response.data.links;
                    console.log(app.links);
                });
        }
        /*
        deleteEntry(id, index) {
            if (confirm("Do you really want to delete it?")) {
                var app = this;
                axios.delete('/api/v1/companies/' + id)
                    .then(function (resp) {
                        app.items.splice(index, 1);
                    })
                    .catch(function (resp) {
                        alert("Could not delete company");
                    });
            }
        }
    }*/
    }
}
</script>

<style scoped>

</style>
