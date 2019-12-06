export default class Gate{

    constructor(user){
        this.user = user;
    }


    isAdmin(){
        return this.user.role_id === 1;
    }

    isUser(){
        return this.user.role_id === 2;
    }




}