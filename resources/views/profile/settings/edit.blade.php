@extends('layouts.user')

@section('content')
    <div x-data="usernameComponent()" x-transition x-cloak class="font-grotesk uppercase">
        <h4 class="text-lg font-bold">CONFIGURAÇÕES DA CONTA</h4>

        <div class="mt-10 items-center">
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('profile.settings.edit') }}"
                    class="{{ request()->routeIs('profile.settings.edit') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} border-b-1 flex h-full cursor-pointer items-center px-2">
                    PERFIL
                </a>
                <a href="{{ route('profile.settings.password') }}"
                    class="{{ request()->routeIs('profile.settings.password') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} border-b-1 flex h-full cursor-pointer items-center px-2">
                    SENHA
                </a>
            </div>
        </div>
        <div class="border-b-1 mb-2 -translate-y-[1px] border-gray-400 opacity-30"></div>



        <form method="POST" action="{{ route('profile.settings.update') }}" id="settingsForm" enctype="multipart/form-data"
            class="flex gap-8">
            @csrf

            <div class="w-1/2">
                <span class="my-6 block font-bold uppercase text-gray-400">Perfil</span>

                <div class="flex flex-row justify-between">
                    <label for="username" class="block text-xs font-bold uppercase text-gray-400">NOME DE USUÁRIO</label>
                    <button type="button" x-on:click="createOpen = true"
                        class="cursor-pointer text-gray-400 hover:text-white">
                        <x-fas-pencil class="h-4 w-4" />
                    </button>
                </div>

                <div class="relative mb-6 mt-2">
                    <input type="text" name="username" id="username"
                        value="{{ old('username', auth()->user()->username) }}"
                        class="w-full rounded bg-[#24283F] px-3 py-2 pr-10 text-gray-400 opacity-50 shadow-sm shadow-gray-900"
                        x-model="username" x-bind:disabled="!usernameEditable" @input.debounce.500ms="checkUsername" />
                    @error('username')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror

                    <div class="mt-1 flex items-center gap-1 text-xs font-bold"
                        x-show="username !== '' && username.toLowerCase() !== originalUsername.toLowerCase()">

                        <template x-if="loading">
                            <x-fas-spinner class="h-4 w-4 animate-spin" />
                        </template>

                        <div x-show="!loading && checked" class="flex items-center gap-1">

                            <div x-show="usernameError" class="flex items-center gap-1 text-red-500">
                                <x-fas-exclamation-circle class="h-4 w-4" />
                                <span x-text="usernameError"></span>
                            </div>

                            <div x-show="!usernameError" class="flex items-center gap-1 text-[#1DAA2D]">
                                <x-fas-check-circle class="h-4 w-4" />
                                <span>DÍSPONÍVEL</span>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="mb-6">
                    <label for="display_name" class="block text-xs font-bold uppercase text-gray-400">NOME DE
                        EXIBIÇÃO</label>
                    <input type="text" name="display_name" id="display_name"
                        value="{{ old('display_name', auth()->user()->display_name) }}"
                        class="w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">
                    @error('display_name')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-xs font-bold uppercase text-gray-400">E-MAIL</label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                        class="w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">
                    @error('email')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $emailChanged = old('email', auth()->user()->email) !== auth()->user()->email;
                @endphp

                <div id="current-password-wrapper" class="mb-6"
                    style="{{ $emailChanged ? 'display:block' : 'display:none' }}">
                    <label for="current_password" class="block text-xs font-bold uppercase text-gray-400">
                        PARA MUDAR E-MAIL DIGITE A SENHA ATUAL
                    </label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900" />
                    @error('current_password')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button
                        class="mt-10 w-1/2 cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-3 py-2 font-bold text-white shadow-sm shadow-gray-900 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="usernameError || loading">
                        SALVAR MUDANÇAS
                    </button>
                </div>
            </div>

            <div class="w-1/2 p-2">
                <div class="flex flex-col items-center">
                    <span class="my-6 block font-bold uppercase text-gray-400">IMAGEM DE PERFIL</span>
                    <div class="flex flex-col items-center gap-4">
                        @if (auth()->user()->attachment)
                            <img id="previewImg" src="{{ asset('storage/' . auth()->user()->attachment->filepath) }}"
                                alt="Avatar" class="h-24 w-24 rounded-full" />
                        @else
                            <div id="iconWrapper">
                                <x-fas-circle-user class="h-24 w-24" fill="#fff" />
                            </div>

                            <img id="previewImg" class="hidden h-24 w-24 rounded-full" alt="Preview Avatar" />

                            <button type="button" x-show="resizedImageBase64 !== ''" x-cloak
                                class="font-grotesk text-sm font-bold uppercase text-red-600 hover:underline"
                                x-on:click="clearImage()">
                                Cancelar
                            </button>

                            <label for="file"
                                class="mt-2 cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 font-bold uppercase text-white shadow-sm shadow-black">
                                Envie seu avatar
                            </label>
                            <input id="file" name="file" type="file" accept=".jpg,.jpeg,.png" class="hidden"
                                x-on:change="handleFileChange" />

                            <div x-show="showCropper" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                style="background-color: rgba(91, 112, 131, 0.4);">
                                <div class="w-full max-w-lg rounded-[2px] bg-[#1f2235] p-4">
                                    <h3 class="mb-4 text-lg font-bold text-gray-400">Ajuste sua imagem</h3>
                                    <div>
                                        <img id="imageCropper" class="max-h-96 w-full rounded object-contain" />
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button"
                                            class="cursor-pointer rounded bg-gradient-to-r from-red-500 to-[#E10000] px-4 py-2 font-bold uppercase text-white"
                                            x-on:click="cancelCrop()">Cancelar</button>
                                        <button type="button"
                                            class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 font-bold uppercase text-white"
                                            x-on:click="confirmCrop()">Confirmar</button>
                                    </div>
                                </div>
                            </div>

                            <div x-show="showResized" class="mt-2 flex flex-col items-center gap-2">
                                <img :src="resizedImageBase64" class="h-24 w-24 rounded-full border border-gray-600"
                                    alt="Preview cortado" />
                                <button type="button" class="rounded bg-red-600 px-3 py-1 text-sm text-white"
                                    x-on:click="clearImage()">Cancelar</button>
                            </div>

                            <input type="hidden" name="resizedImage" x-model="resizedImageBase64" />
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <div class="flex gap-8">
            <div class="w-1/2"></div>
            <div class="w-1/2">
                @if (auth()->user()->attachment)
                    <div class="-translate-y-50 flex justify-center">
                        <form action="{{ route('profile.settings.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="cursor-pointer rounded bg-gradient-to-r from-red-500 to-[#E10000] px-4 py-2 font-bold uppercase text-white shadow-sm shadow-black">
                                Remover Avatar
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div x-show="createOpen" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center"
                style="background-color: rgba(91, 112, 131, 0.4);">
                <div class="w-[90%] max-w-md rounded-lg bg-[#1f2235] p-6 text-white shadow-lg">
                    <h2 class="mb-4 text-lg font-bold">Atenção</h2>
                    <p class="text-sm">Você só pode trocar seu nome de usuário a cada 3 meses.</p>
                    <div class="mt-6 flex justify-end gap-2">
                        <button x-on:click="createOpen = false"
                            class="cursor-pointer rounded bg-gray-500 px-4 py-2 text-sm font-bold uppercase text-white hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button
                            x-on:click="
                            createOpen = false;
                            usernameEditable = true;
                            $nextTick(() => {
                                const input = document.getElementById('username');
                                input.disabled = false;
                                input.classList.remove('opacity-50');
                                input.focus();
                            });
                        "
                            class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 text-sm font-bold uppercase text-white">
                            Entendi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const emailInput = document.getElementById('email');
        const currentPasswordWrapper = document.getElementById('current-password-wrapper');
        const originalEmail = @json(auth()->user()->email);

        emailInput.addEventListener('input', () => {
            if (emailInput.value !== originalEmail) {
                currentPasswordWrapper.style.display = 'block';
            } else {
                currentPasswordWrapper.style.display = 'none';
            }
        });
    </script>

    <script>
        function usernameComponent() {
            return {
                createOpen: false,
                username: '{{ auth()->user()->username }}',
                originalUsername: '{{ auth()->user()->username }}',
                usernameEditable: false,
                usernameError: '',
                loading: false,
                checked: false,

                resizedImageBase64: '',
                showResized: false,
                showCropper: false,
                cropper: null,

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();

                    reader.onload = e => {
                        const image = document.getElementById('imageCropper');
                        image.src = e.target.result;

                        this.showCropper = true;

                        this.$nextTick(() => {
                            if (this.cropper) {
                                this.cropper.destroy();
                            }

                            this.cropper = new Cropper(image, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                movable: true,
                                zoomable: true,
                                scalable: false,
                                ready() {
                                    const containerData = this.cropper.getContainerData();
                                    const cropBoxSize = 300;
                                    const left = (containerData.width - cropBoxSize) / 2;
                                    const top = (containerData.height - cropBoxSize) / 2;

                                    this.cropper.setCropBoxData({
                                        left,
                                        top,
                                        width: cropBoxSize,
                                        height: cropBoxSize
                                    });
                                }
                            });
                        });
                    };

                    reader.readAsDataURL(file);
                },

                cancelCrop() {
                    this.showCropper = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    this.resizedImageBase64 = '';
                    this.showResized = false;
                    document.getElementById('file').value = '';
                },

                confirmCrop() {
                    if (!this.cropper) return;

                    const canvas = this.cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingQuality: 'high'
                    });

                    this.resizedImageBase64 = canvas.toDataURL('image/png');
                    this.showCropper = false;

                    const previewImg = document.getElementById('previewImg');
                    previewImg.src = this.resizedImageBase64;
                    previewImg.classList.remove('hidden');

                    const iconWrapper = document.getElementById('iconWrapper');
                    if (iconWrapper) iconWrapper.style.display = 'none';

                    this.cropper.destroy();
                    this.cropper = null;
                },

                clearImage() {
                    this.resizedImageBase64 = '';
                    this.showResized = false;
                    document.getElementById('file').value = '';

                    const previewImg = document.getElementById('previewImg');
                    previewImg.src = '';
                    previewImg.classList.add('hidden');

                    const iconWrapper = document.getElementById('iconWrapper');
                    if (iconWrapper) iconWrapper.style.display = 'block';
                },

                checkUsername() {
                    if (!this.usernameEditable) return;

                    const trimmed = this.username.trim();

                    if (
                        trimmed === '' ||
                        trimmed.toLowerCase() === this.originalUsername.toLowerCase()
                    ) {
                        this.usernameError = '';
                        this.checked = false;
                        return;
                    }

                    this.loading = true;
                    this.checked = false;

                    fetch(`{{ route('check.username') }}?username=${encodeURIComponent(this.username)}`)
                        .then(res => {
                            if (!res.ok) throw new Error("Erro na requisição");
                            return res.json();
                        })
                        .then(data => {
                            this.usernameError = data.exists ? 'Nome de usuário já está em uso.' : '';
                            this.checked = true;
                        })
                        .catch(err => {
                            console.error(err);
                            this.usernameError = 'Erro ao verificar nome';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            };
        }
    </script>
@endpush
