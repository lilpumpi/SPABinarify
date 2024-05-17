//Lanza las peticiones rest de las suscripciones

class SuscriptionsService {
    constructor() {
  
    }
  
    findAllSuscriptions() {
      return $.get(AppConfig.backendServer+'/rest/suscription');
    }
  
    //Devuelve la suscripcion a partir de un switch_id
    findSuscription(switchId) {
      return $.get(AppConfig.backendServer+'/rest/suscription/' + switchId);
    }
  
    deleteSuscription(id) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/suscription/' + id,
        method: 'DELETE'
      });
    }
  
    addSuscription(suscription) {
      return $.ajax({
        url: AppConfig.backendServer+'/rest/suscription',
        method: 'POST',
        data: JSON.stringify(suscription),
        contentType: 'application/json'
      });
    }
  
  }
  
 
