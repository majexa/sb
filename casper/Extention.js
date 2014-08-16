module.exports = {

  fillAuthForm: function() {
    this.fill('form#formAuth', {
      authLogin: 'admin',
      authPass: '1234'
    });
  }

};