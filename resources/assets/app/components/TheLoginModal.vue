<template>
  <v-dialog
    :value="visible"
    max-width="360"
    @input="close"
  >
    <v-layout
      align-center
      justify-center
    >
      <v-flex xs12>
        <v-card class="elevation-12">
          <v-toolbar dark>
            <v-toolbar-title>{{ $t('forms.login.title') }}</v-toolbar-title>
            <v-spacer/>
          </v-toolbar>
          <v-card-text>
            <v-form @keyup.native.enter="login">
              <v-text-field
                v-validate="'required|email'"
                v-model="email"
                :error-messages="errors.collect('email')"
                :label="$t('forms.login.inputs.email.label')"
                :data-vv-as="$t('forms.login.inputs.email.name')"
                prepend-icon="email"
                name="email"
                type="text"
                data-vv-name="email"
                required
              />
              <v-text-field
                v-validate="'required'"
                v-model="password"
                :append-icon="passwordHidden ? 'visibility' : 'visibility_off'"
                :type="passwordHidden ? 'password' : 'text'"
                :error-messages="errors.collect('password')"
                :label="$t('forms.login.inputs.password.label')"
                :data-vv-as="$t('forms.login.inputs.password.name')"
                prepend-icon="lock"
                name="password"
                data-vv-name="password"
                required
                @click:append="() => (passwordHidden = !passwordHidden)"
              />
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-layout row
                      wrap
                      justify-end
            >
              <v-spacer/>
              <v-btn color="default"
                     @click="close"
              >
                {{ $t('common.buttons.close') }}
              </v-btn>
              <v-btn color="primary"
                     @click="login"
              >
                {{ $t('forms.login.buttons.login') }}
              </v-btn>
            </v-layout>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-dialog>
</template>

<script>
import AuthService from '../services/AuthService';
import FormValidationMixin from '../mixins/FormValidationMixin';
import ModalFormMixin from '../mixins/ModalFormMixin';

export default {
  name:   'TheLoginModal',
  mixins: [
    FormValidationMixin,
    ModalFormMixin,
  ],
  data() {
    return {
      passwordHidden: true,
      email:          '',
      password:       '',
    };
  },
  methods: {
    /**
     * Performs login request.
     */
    async login() {
      if (!await this.revalidateForm()) {
        return;
      }
      AuthService.login(this.email, this.password)
        .then(() => {
          this.close(true);
        }).catch(error => {
          if (this.isValidationError(error)) {
            this.handleValidationError(error.response.data.errors);
          }
        });
    },
    /**
     * Clears form fields.
     */
    clearForm() {
      this.email = '';
      this.password = '';
      this.passwordHidden = true;
    },
    /**
     * Closes modal window.
     */
    close(authResult = false) {
      this.clearForm();
      this.$emit('close', authResult);
    },
  },
};
</script>
