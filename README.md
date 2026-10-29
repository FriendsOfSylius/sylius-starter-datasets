# sylius-starter/datasets

Ready-made demo datasets for the Sylius Starter import package: each one is a catalog (`products.yaml`,
`collections.yaml`) shipped with its images (`media/`) and a `dataset.yaml` (name, description, suggested theme), so a
demo shop is created from it without any AI call or download.

| Dataset        | Catalog                                                       | Suggested theme |
|----------------|---------------------------------------------------------------|-----------------|
| `cellar`       | Seventeen wines from fictional estates, in seven regions      | Cellar          |
| `groove`       | Sixteen fictional vinyl records, in six genres                | Groove          |
| `home`         | Eighteen pieces of furniture, lighting, linen and tableware   | Hearth          |
| `outdoor`      | Eighteen pieces of hiking, climbing and camping gear          | Summit          |
| `retro-gaming` | Sixteen retro consoles, games and accessories, on six shelves | Retro Gaming    |
| `roastery`     | Sixteen specialty coffees and brewing tools, on six shelves   | Roastery        |
| `supermarket`  | Nine everyday groceries, one per supermarket aisle            | Drive           |
| `teahouse`     | Sixteen fine teas and pieces of teaware, on six shelves       | Teahouse        |
| `tech`         | Sixteen devices from fictional brands, in six departments     | Tech            |
| `video-games`  | Sixteen fictional video games, in six genres                  | Arena           |

## Installation

In your Castor project:

```bash
castor composer require sylius-starter/datasets
```

Or install every Sylius Starter package at once with `sylius-starter/sylius-starter`.

```bash
castor sylius:import:dataset:list
castor sylius:import:shop:create cellar
```

Read the [documentation](https://github.com/FriendsOfSylius/sylius-starter/blob/main/docs/import/datasets.md) for usage.

## Contributing

This repository is a **read-only split** of the
[FriendsOfSylius/sylius-starter](https://github.com/FriendsOfSylius/sylius-starter) monorepo.
Please open issues and pull requests there.
