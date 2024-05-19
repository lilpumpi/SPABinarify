class MainComponent extends Fronty.RouterComponent {
  constructor() {
    super('frontyapp', Handlebars.templates.main, 'maincontent');

    // models instantiation
    // we can instantiate models at any place
    this.userModel = new UserModel();
    this.dashboardModel = new DashboardModel();
    this.switchsModel = new SwitchsModel();
    this.suscriptionsModel = new SuscriptionsModel();
    this.userService = new UserService();

    super.setRouterConfig({
      switchs: {
        component: new SwitchsComponent(this.switchsModel, this.userModel, this),
        title: 'Switchs'
      },  
      dashboard: {
        component: new DashboardComponent(this.dashboardModel, this.userModel, this),
        title: 'Switchs'
      },       
      'view-switch': {
        component: new SwitchViewComponent(this.switchsModel, this.userModel, this),
        title: 'Switch'
      },   
      'view-public-switch': {
        component: new SwitchPublicViewComponent(this.switchsModel, this.userModel, this),
        title: 'Public Switch'
      },  
      'add-switch': {
        component: new SwitchAddComponent(this.switchsModel, this.userModel, this),
        title: 'Add Switch'
      },
      suscriptions: {
        component: new SuscriptionsComponent(this.suscriptionsModel, this.userModel, this),
        title: 'Suscriptions'
      },
      login: {
        component: new LoginComponent(this.userModel, this),
        title: 'Login'
      },
      register: {
        component: new RegisterComponent(this.userModel, this),
        title: 'Login'
      },
      defaultRoute: 'login'
    });

    
    Handlebars.registerHelper('currentPage', () => {
          return super.getCurrentPage();
    });

    this.addChildComponent(this._createLanguageComponent());


    //Eventos para abrir y cerrar el menu desplegable
    this.addEventListener('click', '#menu-icon', (event) => {
      var containerMenu = document.getElementById('containerMenu');
      containerMenu.style.display = 'block';
      document.body.style.overflow = 'hidden';
    });

    this.addEventListener('click', '#cerrar', (event) => {
      var containerMenu = document.getElementById('containerMenu');
      containerMenu.style.display = 'none';
      document.body.style.overflow = 'auto';
    });

    this.addEventListener('click', '#logoutbutton', () => {
      var containerMenu = document.getElementById('containerMenu');
      containerMenu.style.display = 'none';
      document.body.style.overflow = 'auto';
      this.userModel.logout();
      this.userService.logout();
      super.goToPage('login');
    });

  }

  start() {
    // override the start() function in order to first check if there is a logged user
    // in sessionStorage, so we try to do a relogin and start the main component
    // only when login is checked
    this.userService.loginWithSessionData()
      .then((logged) => {
        if (logged != null) {
          this.userModel.setLoggeduser(logged);
        }
        super.start(); // now we can call start
      });
  }


  _createLanguageComponent() {
    var languageComponent = new Fronty.ModelComponent(Handlebars.templates.language, this.routerModel, 'languagecontrol');
    // language change links
    languageComponent.addEventListener('click', '#englishlink', () => {
      I18n.changeLanguage('default');
      document.location.reload();
    });

    languageComponent.addEventListener('click', '#spanishlink', () => {
      I18n.changeLanguage('es');
      document.location.reload();
    });

    return languageComponent;
  }  
}
