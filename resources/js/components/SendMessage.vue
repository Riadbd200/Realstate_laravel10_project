<template>
    <div>
       <!-- Button trigger modal -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#chat">
  Live Chat
</button>

<!-- Modal -->
<div class="modal fade" id="chat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="exampleModalLabel">Chat With  {{receivername}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form @submit.prevent="sendMsg()">
      <div class="modal-body">
        <div class="form-group">
            <textarea  class="form-control" v-model="form.msg" rows="5" placeholder="Type your message"></textarea>
            <span class="text-success" v-if="successMsg.message">{{successMsg.message}}</span>
            <span class="text-danger" v-if="errors.msg">{{errors.msg[0]}}</span>
        </div>
      </div>
    
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </div>
       </form>
       
    </div>
  </div>
</div>
    </div>
</template>

<script>
export default{
  props: ['receiverid', 'receivername'],

  data(){
    return{
     
      form: {
        msg: "",
        receiver_id: this.receiverid,
      },

      errors: {},
      successMsg: {},

    }
  },


  methods: {
    sendMsg(){
        axios.post('/send-message', this.form)
        .then((res)=>{
          this.form.msg = "";
          this.successMsg = res.data;
        
          $('#chat').modal('hide');
        }).catch((err)=>{
          this.errors = err.response.data.errors;
        })
    },

 
  }
}
</script>

