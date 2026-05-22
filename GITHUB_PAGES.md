# GitHub Pages setup

This branch is designed to run on GitHub Pages without PHP hosting.

## How it works

- `scripts/build-static.php` renders `index.php` into `dist/index.html`.
- Static assets are copied into `dist/`.
- `.github/workflows/pages.yml` deploys `dist/` to GitHub Pages whenever `github-pages-static` is pushed.
- `/admin/` is a static admin page. It edits the JSON files in `data/` through the GitHub API and saves changes as commits.

There is no Node.js server and no PHP server in production. GitHub Actions only uses PHP during the build.

## Enable Pages

In the GitHub repository:

1. Go to **Settings > Pages**.
2. Set **Build and deployment** to **GitHub Actions**.
3. Push the `github-pages-static` branch.
4. Open the deployed Pages URL.

## Admin token

The static admin page needs a GitHub token because GitHub Pages cannot run server-side login code.

Create a fine-grained personal access token:

1. GitHub > **Settings > Developer settings > Personal access tokens > Fine-grained tokens**.
2. Select repository: `nienkevanunen/jouw-kapper`.
3. Repository permissions: **Contents: Read and write**.
4. Keep the expiration short enough to be safe, but long enough for the owner to use comfortably.
5. Open `/admin/`, paste the token, load a JSON file, edit, and save.

The token is only used in the browser. If "Onthoud token" is enabled, it is stored in localStorage on that device.

## Contact form

GitHub Pages cannot run `contactform/contactform.php`. The static version submits to Web3Forms at `https://api.web3forms.com/submit` with the configured access key.

## Limitations

- The static admin currently edits JSON content, not image uploads.
- Uploaded gallery/promotion images still need to be added to `img/` through git or GitHub's web UI.
- After saving in admin, GitHub Pages needs a short rebuild before the public page updates.
