<template>
  <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38">
    <defs>
        <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" :id="_uid">
            <stop :stop-color="color" stop-opacity="0" offset="0%"/>
            <stop :stop-color="color" stop-opacity=".631" offset="63.146%"/>
            <stop :stop-color="color" offset="100%"/>
        </linearGradient>
    </defs>
    <g fill="none" fill-rule="evenodd">
        <g transform="translate(1 1)">
            <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" :stroke="`url(#${_uid})`" stroke-width="2" transform="rotate(195.282 18 18)">
                <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"/>
            </path>
            <circle :fill="color" cx="36" cy="18" r="1" transform="rotate(195.282 18 18)">
                <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s" repeatCount="indefinite"/>
            </circle>
        </g>
    </g>
</svg>
</template>

<script>
export default {
  props: {
    color: {
      type: String,
      default: 'white',
    }
  }
}
</script>
