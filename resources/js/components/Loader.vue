<template>
    <div class="wrapper">
        <div class="my-loader"></div>
    </div>
</template>

<script>
export default {
    name: "Loader"
};
</script>

<style lang="scss" scoped>
$color: #6751f6;
$size: 8px;
$height: 30px;
$gap: 12px;
$duration: 0.8s;
$border: 8px;

@mixin loader09 {
    width: $size;
    height: $height;
    background: $color;
    position: relative;
    animation: loader09 $duration ease-in-out infinite;
    animation-delay: ($duration/5) * 2;
    margin: 0 auto;
    border-radius: 10px;
    &:after,
    &:before {
        content: "";
        position: absolute;
        width: $size;
        height: $height;
        background: $color;
        animation: loader09 $duration ease-in-out infinite;
        border-radius: 10px;
    }
    &:before {
        right: $size + $gap;
        animation-delay: ($duration/5) * 1;
    }
    &:after {
        left: $size + $gap;
        animation-delay: ($duration/5) * 3;
    }
    @keyframes loader09 {
        0%,
        80%,
        100% {
            box-shadow: 0 0 0 $color, 0 0 0 $color;
        }
        30% {
            box-shadow: 0 (-$gap) 0 $color, 0 $gap 0 $color;
        }
    }
}

@mixin loader-rotate {
    @keyframes loader-rotate {
        0% {
            transform: rotate(0);
        }
        100% {
            transform: rotate(360deg);
        }
    }
}
@mixin loader-scale {
    @keyframes loader-scale {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            transform: scale(1);
            opacity: 0;
        }
    }
}
.wrapper {
    height: 100vh;
}
.my-loader {
    @include loader09;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
