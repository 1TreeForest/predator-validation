const Fs = require('fs').promises;
const FsExtra = require('fs-extra');
const { dirname, sep } = require('path');
const LightningCSS = require('lightningcss');
const Sass = require('sass-embedded');

module.exports.compile = async (file) => {
  const cssFile = file.replace(`${sep}scss${sep}`, `${sep}css${sep}`)
    .replace('.scss', '.css').replace(`${sep}build${sep}media_source${sep}`, `${sep}media${sep}`);

  let compiled;
  try {
    compiled = Sass.renderSync({ file });
  } catch (error) {
    // eslint-disable-next-line no-console
    console.error(error.formatted);
    process.exitCode = 1;
  }

  // Auto prefixing
  const { code } = LightningCSS.transform({
    code: Buffer.from(compiled.css.toString()),
    minify: false,
  });

  // Ensure the folder exists or create it
  await FsExtra.mkdirs(dirname(cssFile), {});
  await Fs.writeFile(
    cssFile,
    code,
    { encoding: 'utf8', mode: 0o644 },
  );

  const cssMin = LightningCSS.transform({
    code: Buffer.from(code),
    minify: true,
    exclude: LightningCSS.Features.VendorPrefixes,
  });

  // Ensure the folder exists or create it
  FsExtra.mkdirs(dirname(cssFile.replace('.css', '.min.css')), {});
  await Fs.writeFile(
    cssFile.replace('.css', '.min.css'),
    cssMin.code,
    { encoding: 'utf8', mode: 0o644 },
  );

  // eslint-disable-next-line no-console
  console.log(`✅ SCSS File compiled: ${cssFile}`);
};
