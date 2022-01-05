<template>
    <div class="my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8 mx-4">
        <!-- <div class="align-middle inline-block justify-between max-w-full shadow overflow-x-auto bg-white shadow-dashboard px-8 pt-3 w-full"> -->
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th style="text-transform: capitalize" class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-800 tracking-wider" v-if="checkbox">  </th>
                        <th v-for="(header,headerI) in headers" :key="headerI" style="text-transform: capitalize" class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-800 tracking-wider">
                            <div class="flex justify-center">
                                <p class="flex items-center float-left mr-1">{{header.display }}</p>
                                <div class="float-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill sort-btn" viewBox="0 0 16 16" v-if="!(sort == header.column && order == 'ASC')" @click="sortFtn(header.column,'ASC',$event)" >
                                        <path d="M7.247 4.86l-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill sort-btn" viewBox="0 0 16 16" v-if="!(sort == header.column && order == 'DESC')" @click="sortFtn(header.column,'DESC',$event)">
                                        <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </th>
                        <th style="text-transform: capitalize" class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 text-gray-800 tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody>
                        <tr v-for="(tableData,tableDataIndex) in rows.data" :key="tableDataIndex"  :class="tableDataIndex%2==0? 'bg-gray-200':'bg-gray-100' " >
                            <td class="border-gray-300 text-sm" v-if="checkbox"><input type="checkbox" name="id" :value="tableData" v-model="selected" @change="$emit('check',selected)"></td>
                            <td v-for="(header,headerI) in headers" :key="headerI" v-html="tableData[header.column]" class="px-6 py-4 whitespace-no-wrap border-b border-gray-300 text-sm leading-5"></td>
                            <td>
                                <div v-for="(action, actionKey) in tableData.action" :key="actionKey" class="inline-block flex">
                                    <inertia-link v-if="!action.newtab" :href="action.link" v-bind:class="action.class" class="rounded text-sm text-white py-1 px-2" style="margin:3px">{{action.name}}</inertia-link>
                                    <a :href="action.link" v-if="action.newtab" v-bind:class="action.class" class="rounded text-sm text-white py-1 px-2" style="margin:3px" target="_blank">{{action.name}}</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <div class="sm:flex-1 sm:flex sm:items-center sm:justify-between mt-4 work-sans">
            <div>
                <p class="text-sm leading-5 text-blue-700">
                    Showing
                    <span class="font-medium">{{rows.from}}</span>
                    to
                    <span class="font-medium">{{rows.to}}</span>
                    of
                    <span class="font-medium">{{rows.total}}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex shadow-sm">
                    <div v-if="rows.prev_page_url != null">
                        <a @click.prevent="paginationFunction(rows.prev_page_url)" class="cursor-pointer relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-600 hover:text-gray-800 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Previous">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div>
                        <a v-for="link in links" :key="link.label" @click.prevent="paginationFunction(link.url)" class="-ml-px cursor-pointer relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-blue-700 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-tertiary active:text-gray-700 transition ease-in-out duration-150 hover:bg-tertiary">
                            {{link.label}}
                        </a>
                    </div>
                    <div v-if="rows.next_page_url != null">
                        <a @click.prevent="paginationFunction(rows.next_page_url)" class="-ml-px cursor-pointer relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-600 hover:text-gray-800 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Next">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </nav>
            </div>
        <!-- </div> -->
    </div>
</div>

</template>

<script>

export default {
    props: {
        pagination:{
            type: Object
        },
        headers:{
            type: Array
        },
        action:{
            type: Object
        },
        checkbox:{
            type: Boolean,
            default: false
        },
        selectAll:{
            type: Boolean,
            default: false
        }
    },
    data(){
        return {
            params: {},
            rows: this.pagination,
            selected:[],
        }
    },

    methods:{
        sortFtn: function(header,order,event){

            let sortBtns = document.getElementsByClassName("sort-btn");
            for (let sortBtn of sortBtns){
                sortBtn.style.display = "block";
            };
            event.currentTarget.style.display = "none";

            this.params.sort=header;
            this.params.order=order;
            this.updateQuery();
        },
        updateQuery(){
            // console.log(this.pagination)
            this.$inertia.get(this.$page.url, {
                sort:this.params.sort,
                order:this.params.order,
                page:null,
            })
        },
        paginationFunction(path){
            this.$inertia.get(path, {
            })
        },
        isNumber(str) {
            if (typeof str != "string") return false // we only process strings!
            // could also coerce to string: str = ""+str
            return !isNaN(str) && !isNaN(parseFloat(str))
        },
        selectedM:function(val,index){
            this.$emit('check',val)
        }
    },
    created:function(){
    },
    watch:{
        selectAll:function(val){
            if(val){
                this.selected = this.rows.data
            }else if(this.selected == this.rows.data){
                this.selected = [];
            }
        }
    },

    computed:{
        links: function(){
            return this.pagination.links.filter(link => Number.isInteger(link.label) || this.isNumber(link.label))
        },
        sort: function(){
            return (this.$page.props && this.$page.props.datatable)?this.$page.props.datatable.sort: "";
        },
        order: function(){
            return (this.$page.props && this.$page.props.datatable)?this.$page.props.datatable.order: "";
        },
    },

    components: {
    },
}
</script>
