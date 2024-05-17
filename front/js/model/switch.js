class SwitchModel extends Fronty.Model {
    
    constructor(id, public_id, private_id, name, description, owner, auto_off_time, last_time, status) {
      super('SwitchModel'); //call super
      
      if (id) {
        this.id = id;
      }
      
      if (public_id) {
        this.public_id = public_id;
      }
      
      if (private_id) {
        this.private_id = private_id;
      }

      if (name) {
        this.name = name;
      }

      if (description) {
        this.description = description;
      }

      if (owner) {
        this.owner = owner;
      }

      if (auto_off_time) {
        this.auto_off_time = auto_off_time;
      }

      if (last_time) {
        this.last_time = last_time;
      }

      if (status) {
        this.status = status;
      }

    }
  
    setName(name) {
      this.set((self) => {
        self.name = name;
      });
    }
  
    setDescription(description) {
      this.set((self) => {
        self.description = description;
      });
    }

    setOwner(owner) {
        this.set((self) => {
          self.owner = owner;
        });
      }

    setAutoOffTime(auto_off_time) {
    this.set((self) => {
        self.auto_off_time = auto_off_time;
    });
    }

    setLastTime(last_time) {
    this.set((self) => {
        self.last_time = last_time;
    });
    }

    setStatus(status) {
        this.set((self) => {
            self.status = status;
        });
    }
  }


  