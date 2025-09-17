module.exports = {
  plugins: [
    require("postcss-import"),
    require("postcss-nesting"),
    require("postcss-short"),
    require("autoprefixer"),
    require("cssnano")({
      preset: "default",
    }),
  ],
};
