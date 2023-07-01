class FormValidator {
  constructor(form, fields) {
    this.form = form
    this.fields = fields
  }

  initialize() {
    this.validateOnEntry()
//    this.validateOnSubmit()
  }

  validateOnEntry() {
    let self = this
    this.fields.forEach(field => {
      const input = document.querySelector(`#${field}`)

      input.addEventListener('focusout', () => {
        self.validateFields(input)
      })
    })
  }
  
  validateOnSubmit() {
    let self = this
    this.form.addEventListener('submit', event => {
        event.preventDefault()
        self.fields.forEach(field => {
        const input = document.querySelector(`#${field}`)
        self.validateFields(input)
      })
    })
  }

  validateFields(field) {

    // Check presence of values
    if (field.value.trim() === "") {
      this.setStatus(field, `${field.previousElementSibling.innerText} cannot be blank`, "error")
    } else {
      this.setStatus(field, null, "success")
    }

    // check for a valid user name
    if (field.id === "user_login") {
      const re = /^[\w_.-]{5,25}$/
      if (re.test(field.value)) {
        this.setStatus(field, null, "success")
      } else {
        this.setStatus(field, "That isn't a valid User Login. Please try again.", "error")
      }
    }

    // check for a valid email address
    if (field.type === "email") {
      const re = /\S+@\S+\.\S+/
      if (re.test(field.value)) {
        this.setStatus(field, null, "success")
      } else {
        this.setStatus(field, "", "error")
      }
    }
	
	// Email confirmation edge case
    if (field.id === "confirm_email") {
      const emailField = this.form.querySelector('#user_email')

      if (field.value.trim() == "") {
        this.setStatus(field, "Email confirmation required", "error")
      } else if (field.value != emailField.value) {
        this.setStatus(field, "Email does not match", "error")
      } else {
        this.setStatus(field, null, "success")
      }
    }

    // check for a valid password
    if (field.type === "password") {
      const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9!@%$\?]).{11,20}$/
      if (re.test(field.value)) {
        this.setStatus(field, null, "success")
      } else {
        this.setStatus(field, "Please enter valid password", "error")
      }
    }

    // Password confirmation edge case
    if (field.id === "confirm_pass") {
      const passwordField = this.form.querySelector('#password')

      if (field.value.trim() == "") {
        this.setStatus(field, "Password confirmation required", "error")
      } else if (field.value != passwordField.value) {
        this.setStatus(field, "Password does not match", "error")
      } else {
        this.setStatus(field, null, "success")
      }
    }
  }

  setStatus(field, message, status) {
    const successIcon = field.parentElement.querySelector('.icon-success')
    const errorIcon = field.parentElement.querySelector('.icon-error')
    const errorMessage = field.parentElement.querySelector('.error-message')

    if (status === "success") {
      if (errorIcon) { 
        errorIcon.classList.add('hidden')
        field.classList.add('success')
      }
      
      if (errorMessage) { 
        errorMessage.innerText = "" 
      }
      successIcon.classList.remove('hidden')
      field.classList.remove('input-error')
    }

    if (status === "error") {
      if (successIcon) { 
        successIcon.classList.add('hidden')
      }
      
      field.parentElement.querySelector('.error-message').innerText = message
      errorIcon.classList.remove('hidden')
      field.classList.add('input-error')
    }
  }
}

const form = document.querySelector('#register')
const fields = ["first_name", "last_name", "user_login", "user_email", "confirm_email", "password", "confirm_pass"]

const validator = new FormValidator(form, fields)
validator.initialize()
