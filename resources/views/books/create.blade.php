<x-app-layout>
    <x-slot name="header">
                <h2 class="dark:bg-gray-800 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    @if (!empty($book))
                        {{ __('Edit project') }}
                    @else
                        {{ __('Add projects') }}
                    @endif
                </h2>
        @include('partials.alerts')
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Content Wrapper. Contains page content -->
                    <div class="content-wrapper">
                        <!-- Main content -->
                        <section class="content dark:bg-gray-800">

                            <!-- Default box -->
                            <div class="card dark:bg-gray-800">
                                <div class="card-body p-0 dark:bg-gray-800">
                                    <div class="content-wrapper">
                                        <!-- Main content -->
                                        <section class="content">
                                            {!! Form::model($book, ['url' => route($route, [$book??null]), 'method' => $book?'PUT':'POST']) !!}
                                            {!! Form::hidden('id') !!}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        {!! Form::text('name', null, ['class' => 'form-control date-control' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name']); !!}
                                                        {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="author">Author</label>
                                                        {!! Form::text('author', null, ['class' => 'form-control date-control' . ($errors->has('author') ? ' is-invalid' : ''), 'id' => 'author']); !!}
                                                        {!! $errors->first('author', '<small class="text-danger">:message</small>') !!}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="price">Price</label>
                                                        {!! Form::number('price', null, ['class' => 'form-control date-control' . ($errors->has('price') ? ' is-invalid' : ''), 'id' => 'price']); !!}
                                                        {!! $errors->first('price', '<small class="text-danger">:message</small>') !!}
                                                    </div>
                                                    <!-- /.card -->
                                                </div>
                                                <div class="col-md-6">

                                                </div>
                                            </div>
                                            <div class="row pb-3">
                                                <div class="col-6">
                                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                                                    <input type="submit" value="Save" class="btn btn-success float-right">
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </section>
                                        <!-- /.content -->
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
