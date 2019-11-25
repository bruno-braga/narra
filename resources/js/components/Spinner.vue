<template>
  <div v-show="show" class="lds-dual-ring"></div>
</template>

<script>
  export default {
    props: [
      'show'
    ],
    name: 'spinner',
    data() {
      return {
      }
    },
  }
</script>

<style>
  .lds-dual-ring {
    display: inline-block;
  }
  .lds-dual-ring:after {
    content: " ";
    display: block;
    width: 46px;
    height: 46px;
    margin: 1px;
    border-radius: 50%;
    border: 5px solid #6c757d;
    border-color: #6c757d transparent #6c757d transparent;
    animation: lds-dual-ring 1.2s linear infinite;
  }
  @keyframes lds-dual-ring {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
</style>
