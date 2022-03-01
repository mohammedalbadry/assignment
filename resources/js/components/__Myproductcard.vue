<template>
  <div class="mycard">
      <div class="img">
          <img :src="img" alt="">
      </div>
      <div class="info">
          <h6 class="title">{{name}}</h6>
          <p class="price">{{price}}</p>
      </div>
       <button v-if="!is_added" class="btn btn-sm btn-block btn-success"  @click="addToCart()">add</button>
       <p v-if="is_added" class="btn btn-sm btn-block btn-success disabled m-0">add</p>
  </div>
</template>

<script>
export default {
    name: "productcard",
    props:{
        id: {
            type: Number,
            default: 1
        },
        img: {
            type: String,
            default: "-"
        },
        name: {
            type: String,
            default: "-"
        },
        price: {
            type: String,
            default: "$-"
        },
        is_added:{
            type: Boolean,
            default: false
        }
    },
    methods:{
        addToCart: function (){
            this.$emit('add')
        }
    }

}
</script>

<style>
.mycard {
    padding: 3px;
    box-shadow: 0 1px 5px 0px rgba(0, 0, 0, 0.5);
    margin-bottom: 8px;
}
.mycard .img{
    width: 100%;
    height: 100px;
    overflow: hidden;
    border: none;
}
.mycard .img img{
    width: 100%;
    min-height: 100%;
}
.mycard .info{
    text-align: center;
}
.mycard .info .title{
    color: #343a40;
}
.mycard .info .price{
    color: #7ab33e;
    font-weight: bolder;
}
</style>