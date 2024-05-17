class SuscriptionsModel extends Fronty.Model {

    constructor() {
      super('SuscriptionsModel'); //call super
  
      // model attributes
      this.suscriptions = [];
    }
  
    setSelectedSwitch(suscription) {
      this.set((self) => {
        self.selectedSuscription = suscription;
      });
    }
  
    setSuscriptions(suscriptions) {
      this.set((self) => {
        self.suscriptions = suscriptions;
      });
    }
  }
  