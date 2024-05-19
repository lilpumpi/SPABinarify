/* Main mvcblog-front script */

//load external resources
function loadTextFile(url) {
  return new Promise((resolve, reject) => {
    $.get({
      url: url,
      cache: true,
      beforeSend: function( xhr ) {
        xhr.overrideMimeType( "text/plain" );
      }
    }).then((source) => {
      resolve(source);
    }).fail(() => reject());
  });
}


// Configuration
var AppConfig = {
  backendServer: 'http://localhost/SPABinarify'
  //backendServer: '/mvcblog'
}

Handlebars.templates = {};
Promise.all([
    I18n.initializeCurrentLanguage('js/i18n'),
    loadTextFile('templates/components/main.hbs').then((source) =>
      Handlebars.templates.main = Handlebars.compile(source)),
    loadTextFile('templates/components/language.hbs').then((source) =>
      Handlebars.templates.language = Handlebars.compile(source)),
    loadTextFile('templates/components/user.hbs').then((source) =>
      Handlebars.templates.user = Handlebars.compile(source)),
    loadTextFile('templates/components/login.hbs').then((source) =>
      Handlebars.templates.login = Handlebars.compile(source)),
    loadTextFile('templates/components/switchs-table.hbs').then((source) =>
      Handlebars.templates.switchstable = Handlebars.compile(source)),
    loadTextFile('templates/components/switch-row.hbs').then((source) =>
      Handlebars.templates.switchrow = Handlebars.compile(source)),
    loadTextFile('templates/components/switch-view.hbs').then((source) =>
    Handlebars.templates.switchview = Handlebars.compile(source)),
    loadTextFile('templates/components/switch-add.hbs').then((source) =>
    Handlebars.templates.switchadd = Handlebars.compile(source)),
    loadTextFile('templates/components/suscriptions-list.hbs').then((source) =>
    Handlebars.templates.suscriptionslist = Handlebars.compile(source)),
    loadTextFile('templates/components/suscription-item.hbs').then((source) =>
    Handlebars.templates.suscriptionitem = Handlebars.compile(source)),
    loadTextFile('templates/components/switch-public-view.hbs').then((source) =>
    Handlebars.templates.switchpublicview = Handlebars.compile(source)),
    loadTextFile('templates/components/dashboard.hbs').then((source) =>
    Handlebars.templates.dashboard = Handlebars.compile(source)),
    loadTextFile('templates/components/dashboard-switch.hbs').then((source) =>
    Handlebars.templates.dashboardswitch = Handlebars.compile(source)),
    loadTextFile('templates/components/dashboard-suscription.hbs').then((source) =>
    Handlebars.templates.dashboardsuscription = Handlebars.compile(source)),
    loadTextFile('templates/components/register.hbs').then((source) =>
    Handlebars.templates.register = Handlebars.compile(source)),
    loadTextFile('templates/components/modal.hbs').then((source) =>
    Handlebars.templates.modal = Handlebars.compile(source))
    ])
  .then(() => {
    $(() => {
      new MainComponent().start();
    });
  }).catch((err) => {
    alert('FATAL: could not start app ' + err);
  });
