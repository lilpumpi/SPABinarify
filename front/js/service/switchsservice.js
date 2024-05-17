//Lanza las peticiones rest de los switches

class SwitchsService {
    constructor() {
  
    }
  
    findAllSwitchs() {
      return $.get(AppConfig.backendServer+'/rest/switch');
    }
  
    findSwitch(id) {
      return $.get(AppConfig.backendServer+'/rest/switch/' + id);
    }

    deleteSwitch(id) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch/' + id,
        method: 'DELETE'
      });
    }
  
    updateSwitch(switchDevice) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch/' + switchDevice.private_id,
        method: 'PUT',
        data: JSON.stringify(switchDevice),
        contentType: 'application/json'
      });
    }
  
    addSwitch(switchDevice) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/switch',
        method: 'POST',
        data: JSON.stringify(switchDevice),
        contentType: 'application/json'
      });
    }
  
  }
