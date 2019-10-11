<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" ng-controller="LoginFormController" ng-submit="loginSubmit()">

                        <div class="form-group" ng-class="form.classes.email">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        ng-model="form.data.email"
                                        autofocus>
                            </div>
                        </div>

                        <div class="form-group" ng-class="form.classes.password">
                            <label for="password" class="col-md-4 control-label">Hasło</label>

                            <div class="col-md-6">
                                <input
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        ng-model="form.data.password"
                                        name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Język</label>

                            <div class="col-md-6">
                                <select name="language" id="language" class="form-control"
                                        ng-options="option.name for option in form.languages track by option.id"
                                        ng-model="form.data.lang"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input
                                                type="checkbox"
                                                name="remember"
                                                ng-model="form.data.remember"
                                        >
                                        Pamiętaj mnie
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Loguj
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>