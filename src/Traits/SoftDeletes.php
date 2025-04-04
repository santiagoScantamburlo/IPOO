<?php

namespace Ipoo\Src\Traits;

trait SoftDeletes
{
    /**
     * Sets the deleted_at column to the date when it was deleted instead of erasing the record from the database
     * 
     * @return bool
     */
    public function softDelete(): bool
    {
        return $this->where("id", $this->id)
            ->update([
                "deleted_at" => "NOW()"
            ]);
    }

    /**
     * Sets the deleted_at column to null in order to restore the record that was soft deleted
     * 
     * @return bool
     */
    public function restore(): bool
    {
        return $this->where("id", $this->id)
            ->update([
                "deleted_at" => null
            ]);
    }

    /**
     * Sets the withDeleted flag to true in order to get all records even if they were softDeleted
     * 
     * @return self
     */
    public function withDeleted(): self
    {
        $this->withDeleted = true;
        return $this;
    }
}
