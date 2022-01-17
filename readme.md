# The Lorry Email Driver For Laravel

## Installation
Installing via composer
```sh
composer require shaungoh01/open-inertia-datatable-laravel
```
Publishing VueJs table Component
```sh
php artisan vendor:publish --tag=datatable-vuejs
```
## Instruction to use
There is 2 part, 1st is to build proper datatable query using datatable() in a query builder like:
```php
$users = User::datatable()->paginate(50);
```
You should pass the result to front-end and the second part is to use the paginate result in front-end.
We can start by imoprting the datatable that we publish earlier
```js
import Datatable from '@/InertiaDatatable/Datatable.vue'

export default defineComponent({
    ...
    components: {
        Datatable,
    },
})
```
Make sure the datatable result is pass to front-end
```js
props: {
    users:{
        type: Object
    },
},
```
Now we need to declare an array for header. This is so that we can easily show what column we want in the table
```js
data(){
    return {
        headers:[
            {
                display:"Id",
                column:"id"
            },
            {
                display:"Email",
                column:"email"
            }
        ]
    }
},
```
After All of this, we can simply just use the datatable html tag by
```html
<datatable :headers="headers" :pagination="users"></datatable>
```