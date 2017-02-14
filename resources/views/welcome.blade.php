@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="content">
            <div class="title">
                <p>@{{ message }}</p>
                <input v-model="message">
                <div>
                    <ul>
                        <li v-for="todo in todos">
                            @{{ todo.text }}
                        </li>
                    </ul>
                </div>
                <div>
                    <button v-on:click="reverseMessage">反转消息</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('js/vue.js')}}"></script>
    <script type="text/javascript">
        new Vue({
            el: '.title',
            data: {
                message: 'Hello Laravel!',
                todos: [
                    { text: 'Learn Laravel' },
                    { text: 'Learn Vue.js' },
                    { text: 'At LaravelAcademy.org' }
                ]
            },
            methods: {
                reverseMessage: function () {
                    this.message = this.message.split('').reverse().join('')
                }
            }
        })
    </script>
@endsection
