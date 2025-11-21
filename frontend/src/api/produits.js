import api from "./axios";

export function getproduits(page, limit) {
  return api.get("/produits", {
    params: {
      page,
      limit,
    },
  });
}

export function getproduit(id) {
  return api.get(`/produits/${id}`);
}