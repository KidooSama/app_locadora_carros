<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card app-login-card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock mr-2"></i> Acesso ao sistema
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="" @submit.prevent="login($event)">
                            <input type="hidden" name="_token" :value="csrf_token">

                            <div class="form-group">
                                <label for="email" class="form-label-custom">E-mail</label>
                                <input id="email" v-model="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus placeholder="seu@email.com">
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label-custom">Senha</label>
                                <input id="password" v-model="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Mantenha-me conectado
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-block py-2">
                                    Entrar
                                </button>
                                <div class="text-center mt-3">
                                    <a class="small text-muted" href="">
                                        Esqueceu sua senha?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['csrf_token'],
        data() {
            return {
                email: '',
                password: ''
            }
        },
        methods: {
            login(e) {
                let url = 'http://localhost:8000/api/login'

                axios.post(url, {
                    email: this.email,
                    password: this.password
                })
                .then(response => {
                    let token = response.data.token

                    if (token) {
                        document.cookie = `token=${token}; SameSite=Lax`
                    }

                    e.target.submit()
                })
                .catch(error => {
                    console.log(error)
                })
            }
        }
    }
</script>
